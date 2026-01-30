<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MONIKA API Documentation</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@5.10.3/swagger-ui.css">
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *, *:before, *:after {
            box-sizing: inherit;
        }
        body {
            margin: 0;
            background: #fafafa;
        }
        .swagger-ui .topbar {
            background-color: #1a237e;
        }
        .swagger-ui .topbar .download-url-wrapper .download-url-button {
            background: #303f9f;
        }
        .custom-header {
            background: linear-gradient(135deg, #1a237e 0%, #303f9f 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .custom-header h1 {
            margin: 0;
            font-size: 28px;
        }
        .custom-header p {
            margin: 10px 0 0;
            opacity: 0.8;
        }
        .back-link {
            position: fixed;
            top: 15px;
            left: 15px;
            background: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            color: #1a237e;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        .back-link:hover {
            background: #e8eaf6;
        }
    </style>
</head>
<body>
    <a href="<?= base_url('dashboard') ?>" class="back-link">
        ← Kembali ke Dashboard
    </a>
    
    <div class="custom-header">
        <h1>🔌 MONIKA API Documentation</h1>
        <p>Sistem Monitoring dan Manajemen Kegiatan Survey</p>
    </div>
    
    <div id="swagger-ui"></div>
    
    <script src="https://unpkg.com/swagger-ui-dist@5.10.3/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.10.3/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            const ui = SwaggerUIBundle({
                url: "<?= base_url('docs/swagger.yaml') ?>",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                persistAuthorization: true,
                displayRequestDuration: true,
                filter: true,
                showExtensions: true,
                showCommonExtensions: true
            });
            window.ui = ui;
        };
    </script>
</body>
</html>
