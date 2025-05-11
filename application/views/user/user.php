<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Master Data</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard'); ?>"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">User List</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <button type="button" class="btn btn-primary px-5" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class='bx bx-plus mr-1'></i>Tambah Data User</button>
        <hr />
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h6 class="mb-0 text-primary">Register User</h6>
                        </div>
                        <hr>
                        <form class="row g-3" method="post" action="<?= base_url('User/addUser'); ?>">
                            <div class="col-12">
                                <label for="name" class="form-label">Nama User</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="role" class="form-label">Role User</label>
                                <select name="role" id="role" class="form-control">
                                    <option value="">Pilih Role</option>
                                    <option value="Owner">Owner</option>
                                    <option value="Supervisor">Supervisor</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="nomor_handphone" class="form-label">Nomor Handphone</label>
                                <input type="text" class="form-control" id="nomor_handphone" name="nomor_handphone" required>
                            </div>
                            <div class="col-md-6">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="nik" name="nik" required>
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nama User</th>
                                <th>Email</th>
                                <th>Role User</th>
                                <th>Created</th>
                                <th>Update</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($account as $a) : ?>
                                <tr>
                                    <td><?= $a['name']; ?>
                                    <td><?= $a['email']; ?></td>
                                    <td><?= $a['role']; ?>
                                    <td><?= $a['created_at']; ?>
                                    <td><?= $a['updated_at']; ?>
                                    <td>
                                        <a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#detailUserModal<?php echo $a['id_user']; ?>">Detail User</a>
                                        <a class="btn btn-danger" href="<?= base_url('User/deleteUser/') . $a['id_user']; ?>">Delete User</a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="detailUserModal<?php echo $a['id_user']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="card-title d-flex align-items-center">
                                                    <div><i class="bx bx-data me-1 font-22 text-primary"></i>
                                                    </div>
                                                    <h6 class="mb-0 text-primary">Edit User</h6>
                                                </div>
                                                <hr>
                                                <input type="hidden" name="id_user" id="id_user" value="<?= $a['id_user']; ?>">
                                                <forms class="row g-3">
                                                    <div class="col-12">
                                                        <label for="name" class="form-label">Nama User</label>
                                                        <input type="text" class="form-control" id="name" value="<?php echo $a['name']; ?>" name="name" required readonly>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="text" class="form-control" id="email" value="<?php echo $a['email']; ?>" name="email" required readonly>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="role" class="form-label">Role User</label>
                                                        <input type="text" class="form-control" id="role" value="<?php echo $a['role']; ?>" name="role" required readonly>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="nomor_handphone" class="form-label">Nomor Handphone</label>
                                                        <input type="text" class="form-control" id="nomor_handphone" value="<?php echo $a['nomor_handphone']; ?>" name="nomor_handphone" required readonly>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="nik" class="form-label">NIK</label>
                                                        <input type="text" class="form-control" id="nik" value="<?php echo $a['nik']; ?>" name="nik" required readonly>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </forms>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>