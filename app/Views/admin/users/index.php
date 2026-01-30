<?= $this->extend('layout/dashboard') ?>

<?= $this->section('header_actions') ?>
<div class="float-sm-right">
    <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Tambah User
    </a>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengguna</h3>
        <div class="card-tools">
            <form action="" method="get">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control float-right" placeholder="Cari user..."
                        value="<?= esc($search) ?>">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="7" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <?= $user['id_user'] ?>
                            </td>
                            <td>
                                <?= $user['fullname'] ?>
                            </td>
                            <td>
                                <?= $user['username'] ?>
                            </td>
                            <td>
                                <?= $user['email'] ?>
                            </td>
                            <td>
                                <?php
                                switch ($user['id_role']) {
                                    case 1:
                                        echo '<span class="badge badge-danger">Administrator</span>';
                                        break;
                                    case 3:
                                        echo '<span class="badge badge-success">PCL</span>';
                                        break;
                                    case 4:
                                        echo '<span class="badge badge-info">Pengolahan</span>';
                                        break;
                                    default:
                                        echo '<span class="badge badge-secondary">User</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?= ($user['is_active']) ? '<span class="text-success"><i class="fas fa-check-circle"></i> Aktif</span>' : '<span class="text-danger">Non-Aktif</span>' ?>
                            </td>
                            <td>
                                <a href="<?= base_url('admin/users/edit/' . $user['id_user']) ?>" class="btn btn-warning btn-xs"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?= base_url('admin/users/delete/' . $user['id_user']) ?>" method="post"
                                    style="display:inline;"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-xs" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        <div class="float-right">
            <?= $pager->links() ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>