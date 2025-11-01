<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="<?php echo base_url('assets/images/logo-perpus.png') ?>" alt="AdminLTELogo" height="100" width="100">
</div>

<nav class="main-header navbar navbar-expand navbar-dark">

    <ul class="navbar-nav">
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown user user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <img src="<?php echo base_url('assets/template/') ?>dist/img/user2-160x160.jpg" class="user-image img-circle elevation-2 alt=" User Image">
                <span class="hidden-xs"> <?php echo $this->session->userdata('nama') ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-primary">
                    <img src="<?php echo base_url('assets/template/') ?>dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                    <p>
                        <?php echo $this->session->userdata('nama') ?>
                    </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                    <div class="fa pull-left" style="margin-right:30px">
                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="fa pull-right">
                        <a href="<?php echo base_url('login/logout') ?>" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                </li>
            </ul>
        </li>

        <li class="nav-item dropdown">
        </li>
        <li class="nav-item">
        </li>
        <li class="nav-item">
        </li>
    </ul>
</nav>