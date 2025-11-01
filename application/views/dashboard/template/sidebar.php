     <style>
.nav-item {
    padding-left: 10px;
}
     </style>
      

<!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4">
          <!-- Brand Logo -->
          <a href="<?php echo base_url('admin') ?>" class="brand-link">
              <img src="<?php echo base_url('assets/images/logo-perpus.png') ?>" alt="AdminLTE Logo"
                  class="brand-image img-circle elevation-3" style="opacity: .8">
              <span class="brand-text font-weight-light">LOKER</span>
          </a>

          <!-- Sidebar -->
          <div class="sidebar">
              <!-- Sidebar user panel (optional) -->
              <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                  <div class="image">
                      <img src="<?php echo base_url('assets/template') ?>/dist/img/user2-160x160.jpg"
                          class="img-circle elevation-2" alt="User Image" />
                  </div>
                  <div class="info">
                      <a href="#" class="d-block"> <?php echo $this->session->userdata('nama') ?></a>
                  </div>
              </div>

              <!-- SidebarSearch Form -->
              <!-- <div class="form-inline">
                  <div class="input-group" data-widget="sidebar-search">
                      <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" />
                      <div class="input-group-append">
                          <button class="btn btn-sidebar">
                              <i class="fas fa-search fa-fw"></i>
                          </button>
                      </div>
                  </div>
              </div> -->

              <!-- Sidebar Menu -->
              <nav class="mt-2">
                  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                      data-accordion="false">
                      <li class="nav-header">Setting</li>
                      <li class="nav-item">
                          <a href="<?php echo base_url('admin/dashboard') ?>" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Dashboard</p>
                          </a>
                      </li>
                    <!--    <li class="nav-item">
                          <a href="<?php echo base_url('admin/blokir') ?>" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Blokir</p>
                          </a>
                      </li>> -->
                      <li class="nav-item">
                          <a href="<?php echo base_url('admin/kunci') ?>" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Kunci</p>
                          </a>
                      </li>
                      <!-- <li class="nav-item">
                          <a href="<?php echo base_url('admin/tamu') ?>" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Tamu</p>
                          </a>
                      </li> -->

                      <li class="nav-header">Laporan</li>
                      <li class="nav-item">
                          <a href="<?php echo base_url('admin/laporan') ?>" class="nav-link">
                              <i class="nav-icon fas fa-file-excel"></i>
                              <p>
                                  Laporan
                                  <!-- <span class="right badge badge-danger">New</span> -->
                              </p>
                          </a>
                      </li>
                      <li class="nav-header">Transaksi</li>
                      <li class="nav-item">
                          <a href="#" class="nav-link">
                              <i class="nav-icon fas fa-key"></i>
                              <p>
                                  Loker
                                  <i class="right fas fa-angle-left"></i>
                              </p>
                          </a>
                          <ul class="nav nav-treeview">
                              <li class="nav-item">
                                  <a href="<?php echo base_url('admin/transaksi_list') ?>" class="nav-link">
                                      <i class="far fa-circle nav-icon"></i>
                                      <p>List</p>
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="<?php echo base_url('admin/transaksi/peminjaman') ?>" class="nav-link">
                                      <i class="far fa-circle nav-icon"></i>
                                      <p>Peminjaman</p>
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="<?php echo base_url('admin/transaksi/pengembalian') ?>" class="nav-link">
                                      <i class="far fa-circle nav-icon"></i>
                                      <p>Pengembalian</p>
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="<?php echo base_url('admin/transaksi/realtime') ?>" class="nav-link">
                                      <i class="far fa-circle nav-icon"></i>
                                      <p>Realtime</p>
                                  </a>
                              </li>
   
				<li class="nav-item">
                                  <a href="<?php echo base_url('admin/transaksi/monitoring') ?>" class="nav-link">
                                      <i class="far fa-circle nav-icon"></i>
                                      <p>Monitoring</p>
                                  </a>
                              </li>
				<li class="nav-item">
                                  <a href="<?php echo base_url('admin/transaksi/tanggungan') ?>" class="nav-link">
                                      <i class="far fa-circle nav-icon"></i>
                                      <p>Tanggungan</p>
                                  </a>
                              </li>

                          </ul>
                      </li>


                      <li class="nav-item">
                          <a href="#" class="nav-link">
                              <i class="nav-icon fas fa-briefcase"></i>
                              <p>
                                  Tas
                                  <i class="right fas fa-angle-left"></i>
                              </p>
                          </a>
                          <ul class="nav nav-treeview">
                              <li class="nav-item">
                                  <a href="<?php echo base_url('admin/transaksi_tas') ?>" class="nav-link">
                                      <i class="far fa-circle nav-icon"></i>
                                      <p>List</p>
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="<?php echo base_url('admin/transaksi_tas/peminjaman') ?>" class="nav-link">
                                      <i class="far fa-circle nav-icon"></i>
                                      <p>Peminjaman</p>
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="<?php echo base_url('admin/transaksi_tas/pengembalian') ?>" class="nav-link">
                                      <i class="far fa-circle nav-icon"></i>
                                      <p>Pengembalian</p>
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="<?php echo base_url('admin/transaksi_tas/monitoring') ?>" class="nav-link">
                                      <i class="far fa-circle nav-icon"></i>
                                      <p>Monitoring</p>
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="<?php echo base_url('admin/transaksi_tas/Tanggungan') ?>" class="nav-link">
                                      <i class="far fa-circle nav-icon"></i>
                                      <p>Tanggungan</p>
                                  </a>
                              </li>
                          </ul>
                      </li>
                      <hr class="sidebar-divider">
                      <li class="nav-item">
                          <a href="<?php echo base_url('login/logout') ?>" class="nav-link">
                              <i class="nav-icon fas fa-sign-out-alt"></i>
                              <p>
                                  Logout
                                  <!-- <span class="right badge badge-danger">New</span> -->
                              </p>
                          </a>
                      </li>
                      <!-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Layout Options
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right">6</span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/layout/top-nav.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Top Navigation</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/top-nav-sidebar.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Top Navigation + Sidebar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/boxed.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Boxed</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/fixed-sidebar.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fixed Sidebar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/fixed-sidebar-custom.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fixed Sidebar <small>+ Custom Area</small></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/fixed-topnav.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fixed Navbar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/fixed-footer.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fixed Footer</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/layout/collapsed-sidebar.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Collapsed Sidebar</p>
                            </a>
                        </li>
                    </ul>
                </li> -->

                  </ul>
              </nav>
              <!-- /.sidebar-menu -->
          </div>
          <!-- /.sidebar -->
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