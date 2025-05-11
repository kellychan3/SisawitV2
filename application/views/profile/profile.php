<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">User</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard'); ?>"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="container">
            <div class="main-body">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center text-center">
                                    <img src="<?= base_url(); ?>assets/images/avatars/avatar-1.png" alt="Admin" class="rounded-circle p-1 bg-primary" width="110">
                                    <div class="mt-3">
                                        <h4><?= $user['name']; ?></h4>
                                        <p class="text-secondary mb-1"><?= $user['email'] ?></p>
                                        <p class="text-muted font-size-sm"><?= $user['role'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <form action="<?= base_url('Profile/changePassword/') . $user['id_user']; ?>" method="POST">
                                <input type="hidden" name="id_user" id="id_user" value="<?= $user['id_user']; ?>">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="mt-0 mb-2 ">
                                            <h4 style="text-align: center;">Change Password</h4>
                                        </div>
                                        <div class="col-sm-3">
                                            <h6 class="mt-2">Password</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="password" name="password" id="password" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="submit" class="btn btn-primary px-4" value="Change Password" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>