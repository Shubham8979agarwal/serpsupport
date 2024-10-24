<footer class="footer">
<div class="container-fluid d-flex justify-content-between">
  <!-- <div class="copyright" >
    2024, made with <i class="fa fa-heart heart text-danger"></i> by
    <a href="#">SerpSupport</a>
  </div> -->
</div>
</footer>
</div>
</div>
    <!--   Core JS Files   -->
    <script src="{{ url('dashboard_assets/js/core/jquery-3.7.1.min.js') }}"></script>

    <script src="{{ url('dashboard_assets/js/core/popper.min.js') }}"></script>
    <script src="{{ url('dashboard_assets/js/core/bootstrap.min.js') }}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ url('dashboard_assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- Moment JS -->
    <script src="{{ url('dashboard_assets/js/plugin/moment/moment.min.js') }}"></script>

    <!-- Chart JS -->
    <script src="{{ url('dashboard_assets/js/plugin/chart.js/chart.min.js') }}"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ url('dashboard_assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

    <!-- Chart Circle -->
    <script src="{{ url('dashboard_assets/js/plugin/chart-circle/circles.min.js') }}"></script>

    <!-- Datatables -->
    <script src="{{ url('dashboard_assets/js/plugin/datatables/datatables.min.js') }}"></script>

    <!-- Bootstrap Notify -->
    <script src="{{ url('dashboard_assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

    <!-- jQuery Vector Maps -->
    <script src="{{ url('dashboard_assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>

    <script src="{{ url('dashboard_assets/js/plugin/jsvectormap/world.js') }}"></script>

    <!-- Sweet Alert -->
    <script src="{{ url('dashboard_assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ url('dashboard_assets/js/kaiadmin.min.js') }}"></script>
    
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>

    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>

    <script type="text/javascript">
      $('#websites').dataTable({
           "paging": false,
           "searching": false,
           "ordering": false,
           "info":     false,
           "scrollX": true
       });
       $('#acld').dataTable({
           "paging": false,
           "searching": false,
           "ordering": false,
           "info":     false,
           "scrollX": true
       });
       $('#sd').dataTable({
           "paging": false,
           "searching": false,
           "ordering": false,
           "info":     false,
           "scrollX": true
       });
    </script>
    <script type="text/javascript">
      document.addEventListener('DOMContentLoaded', function() {
        // Get all sidebar nav items with submenus
        const navItems = document.querySelectorAll('.nav-item[data-bs-toggle="collapse"]');
          navItems.forEach(item => {
                const link = item.querySelector('a[data-bs-toggle="collapse"]');
                const submenu = item.querySelector('.collapse');
                const caret = item.querySelector('.caret .fas');

                link.addEventListener('click', function() {
                    // Collapse other open submenus
                    navItems.forEach(otherItem => {
                        if (otherItem !== item) {
                            const otherSubmenu = otherItem.querySelector('.collapse');
                            if (otherSubmenu) {
                                otherSubmenu.classList.remove('show');
                                const otherCaret = otherItem.querySelector('.caret .fas');
                                if (otherCaret) {
                                    otherCaret.classList.remove('fa-caret-up');
                                    otherCaret.classList.add('fa-caret-down');
                                }
                            }
                        }
                    });
                // Toggle the clicked submenu
                submenu.classList.toggle('show');
                caret.classList.toggle('fa-caret-up');
                caret.classList.toggle('fa-caret-down');
            });
        });
    });

   document.addEventListener('DOMContentLoaded', () => {
  const togglers = document.querySelectorAll('[data-toggle]');
  
    togglers.forEach((btn) => {
      btn.addEventListener('click', (e) => {
         const selector = e.currentTarget.dataset.toggle
         const block = document.querySelector(`${selector}`);
        if (e.currentTarget.classList.contains('active')) {
          block.style.maxHeight = '';
        } else {
          block.style.maxHeight = block.scrollHeight + 'px';
        }
          
         e.currentTarget.classList.toggle('active')
      })
    })
    })   
    </script>
  </body>
</html>