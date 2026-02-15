# ============================================
# MONIKA API Documentation Generator
# ============================================
# Script ini generate dokumentasi API
# dari controller yang ada
# ============================================

$ErrorActionPreference = "Stop"
$ProjectRoot = Split-Path -Parent $PSScriptRoot
$ControllersPath = Join-Path $ProjectRoot "app\Controllers"
$DocsDir = Join-Path $ProjectRoot "docs"
$Timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"

Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  MONIKA API Documentation Generator" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# ============================================
# Function: Parse Controller Methods
# ============================================
function Get-ControllerMethods {
    param([string]$FilePath)
    
    $content = Get-Content $FilePath -Raw
    $methods = @()
    
    # Find all public methods
    $pattern = 'public\s+function\s+(\w+)\s*\(([^)]*)\)'
    $matches = [regex]::Matches($content, $pattern)
    
    foreach ($match in $matches) {
        $methodName = $match.Groups[1].Value
        $params = $match.Groups[2].Value
        
        # Skip constructor and magic methods
        if ($methodName -eq '__construct' -or $methodName.StartsWith('__')) {
            continue
        }
        
        $methods += @{
            Name = $methodName
            Params = $params
        }
    }
    
    return $methods
}

# ============================================
# Function: Determine HTTP Method
# ============================================
function Get-HttpMethod {
    param([string]$MethodName)
    
    $methodName = $methodName.ToLower()
    
    if ($methodName -eq 'index' -or $methodName -eq 'show' -or $methodName -eq 'detail') {
        return 'GET'
    } elseif ($methodName -eq 'store' -or $methodName -eq 'create' -or $methodName -eq 'save') {
        return 'POST'
    } elseif ($methodName -eq 'update' -or $methodName -eq 'edit') {
        return 'PUT/POST'
    } elseif ($methodName -eq 'delete' -or $methodName -eq 'destroy') {
        return 'DELETE/GET'
    } else {
        return 'GET/POST'
    }
}

# ============================================
# Function: Generate API Documentation
# ============================================
function Generate-ApiDocs {
    Write-Host "Scanning controllers..." -ForegroundColor Yellow
    
    $controllers = Get-ChildItem $ControllersPath -Filter "*.php" | 
        Where-Object { $_.Name -ne "BaseController.php" -and $_.Name -ne "Home.php" }
    
    $apiDoc = @"
# MONIKA - API Documentation

**Last Updated:** $Timestamp  
**Base URL:** ``http://localhost/monika/``

---

## Authentication

All API endpoints require authentication via session. User must login first.

**Login Endpoint:**
- **URL:** ``/login``
- **Method:** ``POST``
- **Body:**
  ``````json
  {
    "username": "string",
    "password": "string"
  }
  ``````

---

## Endpoints

"@
    
    foreach ($controller in $controllers | Sort-Object Name) {
        $controllerName = $controller.BaseName
        $controllerPath = $controller.FullName
        
        Write-Host "  Processing $controllerName..." -ForegroundColor Gray
        
        $methods = Get-ControllerMethods -FilePath $controllerPath
        
        if ($methods.Count -eq 0) {
            continue
        }
        
        $apiDoc += @"

### $controllerName

"@
        
        # Determine base route
        $baseRoute = ($controllerName -replace 'Controller$', '').ToLower()
        $baseRoute = $baseRoute -replace '([A-Z])', '-$1'
        $baseRoute = $baseRoute.Trim('-').ToLower()
        
        foreach ($method in $methods) {
            $methodName = $method.Name
            $httpMethod = Get-HttpMethod -MethodName $methodName
            
            # Determine endpoint
            $endpoint = "/$baseRoute"
            if ($methodName -ne 'index') {
                $endpoint += "/$methodName"
            }
            
            # Add parameters if exists
            if ($method.Params -match '\$(\w+)') {
                $paramName = $Matches[1]
                $endpoint += "/{$paramName}"
            }
            
            $apiDoc += @"

#### $methodName

- **URL:** ``$endpoint``
- **Method:** ``$httpMethod``
- **Auth Required:** Yes

"@
            
            # Add description based on method name
            switch ($methodName.ToLower()) {
                'index' {
                    $apiDoc += "- **Description:** Get list of $baseRoute`n"
                    $apiDoc += "- **Response:** HTML view or JSON array`n"
                }
                'detail' {
                    $apiDoc += "- **Description:** Get detail of specific $baseRoute`n"
                    $apiDoc += "- **Response:** HTML view or JSON object`n"
                }
                'new' {
                    $apiDoc += "- **Description:** Show form to create new $baseRoute`n"
                    $apiDoc += "- **Response:** HTML view`n"
                }
                'store' {
                    $apiDoc += "- **Description:** Store new $baseRoute data`n"
                    $apiDoc += "- **Response:** Redirect or JSON`n"
                    $apiDoc += "- **Body:** Form data (application/x-www-form-urlencoded)`n"
                }
                'delete' {
                    $apiDoc += "- **Description:** Delete $baseRoute data`n"
                    $apiDoc += "- **Response:** Redirect or JSON`n"
                }
                default {
                    $apiDoc += "- **Description:** $methodName operation for $baseRoute`n"
                }
            }
            
            $apiDoc += "`n"
        }
        
        $apiDoc += "---`n"
    }
    
    $apiDoc += @"

## Response Format

### Success Response (JSON)
``````json
{
  "status": "success",
  "message": "Operation successful",
  "data": {}
}
``````

### Error Response (JSON)
``````json
{
  "status": "error",
  "message": "Error description",
  "errors": {}
}
``````

### HTML Response
Most endpoints return HTML views for browser access.

---

## CSRF Protection

All POST requests require CSRF token:

``````html
<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
``````

Or in AJAX:
``````javascript
$.ajax({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
``````

---

## Error Codes

- **200** - Success
- **302** - Redirect (after successful operation)
- **400** - Bad Request (validation error)
- **401** - Unauthorized (not logged in)
- **403** - Forbidden (no permission)
- **404** - Not Found
- **500** - Internal Server Error

---

## Rate Limiting

Currently no rate limiting implemented.

---

## Examples

### Example 1: Get Kartu Kendali List

``````bash
GET /kartu-kendali
``````

**Response:**
``````html
<!-- HTML view with table -->
``````

### Example 2: Store Entry Data

``````bash
POST /kartu-kendali/store
Content-Type: application/x-www-form-urlencoded

nks=1234567890&no_ruta=1&status_entry=Clean&is_patch_issue=0
``````

**Response:**
``````json
{
  "status": "success",
  "message": "Data berhasil disimpan."
}
``````

### Example 3: Get Uji Petik List

``````bash
GET /uji-petik
``````

**Response:**
``````html
<!-- HTML view with table -->
``````

---

**Generated by:** MONIKA API Documentation Generator  
**Date:** $Timestamp
"@
    
    # Save to file
    $apiDocFile = Join-Path $DocsDir "API_DOCUMENTATION.md"
    Set-Content -Path $apiDocFile -Value $apiDoc -Encoding UTF8
    
    Write-Host ""
    Write-Host "✓ API Documentation generated successfully!" -ForegroundColor Green
    Write-Host "  File: docs/API_DOCUMENTATION.md" -ForegroundColor Cyan
    Write-Host "  Controllers processed: $($controllers.Count)" -ForegroundColor Cyan
}

# ============================================
# Main Execution
# ============================================

try {
    # Ensure docs directory exists
    if (-not (Test-Path $DocsDir)) {
        New-Item -ItemType Directory -Path $DocsDir -Force | Out-Null
    }
    
    Generate-ApiDocs
    
    Write-Host ""
    Write-Host "================================================" -ForegroundColor Green
    Write-Host "  Done!" -ForegroundColor Green
    Write-Host "================================================" -ForegroundColor Green
    
} catch {
    Write-Host ""
    Write-Host "Error: $_" -ForegroundColor Red
    Write-Host $_.ScriptStackTrace -ForegroundColor Red
    exit 1
}
