<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="index3.html" class="brand-link">
        <!-- <img src="#" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
        <span class="brand-text font-weight-light">LOKER</span>
    </a>

    <div class="sidebar">

        <nav class="mt-2" id="nav1">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- <li class="nav-item menu-open"> -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Dashboard
                            <!-- <span class="right badge badge-danger">New</span> -->
                        </p>
                    </a>
                </li>
            </ul>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- <li class="nav-item menu-open"> -->
                <li class="nav-item">
                    <a href="<?php echo base_url('admin/tamu') ?>" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Tamu
                            <!-- <span class="right badge badge-danger">New</span> -->
                        </p>
                    </a>
                </li>
            </ul>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- <li class="nav-item menu-open"> -->
                <li class="nav-item">
                    <a href="<?php echo base_url('admin/blokir') ?>" class="nav-link">
                        <i class="nav-icon fas fa-ban"></i>
                        <p>
                            Blokir
                            <!-- <span class="right badge badge-danger">New</span> -->
                        </p>
                    </a>
                </li>
            </ul>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- <li class="nav-item menu-open"> -->
                <li class="nav-item">
                    <a href="<?php echo base_url('admin/kunci') ?>" class="nav-link ">
                        <i class="nav-icon fas fa-key"></i>
                        <p>
                            Kunci
                            <!-- <span class="right badge badge-danger">New</span> -->
                        </p>
                    </a>
                </li>
            </ul>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- <li class="nav-item menu-open"> -->
                <li class="nav-item">
                    <a href="<?php echo base_url('admin/laporan') ?>" class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Laporan
                            <!-- <span class="right badge badge-danger">New</span> -->
                        </p>
                    </a>
                </li>
            </ul>

        </nav>

    </div>

</aside>
<script src="<?php echo base_url('assets/plugins') ?>/jquery/jquery.min.js"></script>
<script type="text/javascript">
    $(function() {
        /** add active class and stay opened when selected */
        var url = window.location;



        // for sidebar menu entirely but not cover treeview
        $('ul.nav-sidebar a').filter(function() {
            return this.href == url;
        }).addClass('active');

        // for treeview
        $('ul.nav-treeview a').filter(function() {
            return this.href == url;
        }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');
    })
</script>