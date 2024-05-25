<?php
$id = $_SESSION["id"];
$user = query("SELECT * FROM users WHERE id = $id")[0];
?>
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- User Profile-->
        <div class="user-profile">
            <div class="user-pro-body">
                <div><img src="../assets/images/users/<?= $user["avatar"]; ?>" alt="user-img" class="img-circle"></div>
                <div class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle u-dropdown link hide-menu" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= $user["nama"]; ?> <span class="caret"></span></a>
                    <div class="dropdown-menu animated flipInY">
                        <a href="../profile" class="dropdown-item"><i class="fas fa-user"></i> My Profile</a>
                        <?php
                        if ($user['role'] == 'Admin') {
                            echo '<a href="../users" class="dropdown-item"><i class="fas fa-users"></i> Users Management</a>';
                        } else {
                            echo "";
                        }
                        ?>
                        <div class="dropdown-divider"></div>
                        <a href="../change_password" class="dropdown-item"><i class="fas fa-cog"></i> Change Password</a>
                        <div class="dropdown-divider"></div>
                        <a href="../logout" class="dropdown-item"><i class="fas fa-power-off"></i> Logout</a>

                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-small-cap">--- MASTER DATA</li>
                <li>
                    <a class="waves-effect waves-dark" href="../data_atribut" aria-expanded="false">
                        <i class="far fa-circle text-danger"></i>
                        <span class="hide-menu">Data Atribut</span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect waves-dark" href="../data_kelurahan" aria-expanded="false">
                        <i class="far fa-circle text-success"></i>
                        <span class="hide-menu">Data Kelurahan</span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect waves-dark" href="../data_cluster" aria-expanded="false">
                        <i class="far fa-circle text-info"></i>
                        <span class="hide-menu">Data Cluster</span>
                    </a>
                </li>
                <li class="nav-small-cap">--- NILAI DATA</li>
                <li>
                    <a class="waves-effect waves-dark" href="../nilai_kelurahan" aria-expanded="false">
                        <i class="far fa-circle text-danger"></i>
                        <span class="hide-menu">Nilai Kelurahan</span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect waves-dark" href="../nilai_cluster" aria-expanded="false">
                        <i class="far fa-circle text-success"></i>
                        <span class="hide-menu">Nilai Cluster</span>
                    </a>
                </li>
                <li class="nav-small-cap">--- PROSES PERHITUNGAN</li>
                <li>
                    <a class="waves-effect waves-dark" href="../proses_perhitungan" aria-expanded="false">
                        <i class="far fa-circle text-danger"></i>
                        <span class="hide-menu">Iterasi</span>
                    </a>
                </li>
                <li class="nav-small-cap">--- LAPORAN HASIL PERHITUNGAN</li>
                <li>
                    <a class="waves-effect waves-dark" href="../laporan" aria-expanded="false">
                        <i class="mdi mdi-file-document"></i>
                        <span class="hide-menu">Laporan</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>