<footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <!-- <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="http://www.themekita.com">
                    ThemeKita
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Help </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Licenses </a>
                </li>
              </ul>
            </nav> -->
            <div class="copyright" >
              2024, made with <i class="fa fa-heart heart text-danger"></i> by
              <a href="#">SerpSupport</a>
            </div>
            <!-- <div>
              Distributed by
              <a target="_blank" href="https://themewagon.com/">ThemeWagon</a>.
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

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="{{ url('dashboard_assets/js/setting-demo.js') }}"></script>
    <script src="{{ url('dashboard_assets/js/demo.js') }}"></script>
    <script>
      $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#177dff",
        fillColor: "rgba(23, 125, 255, 0.14)",
      });

      $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#f3545d",
        fillColor: "rgba(243, 84, 93, .14)",
      });

      $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#ffa534",
        fillColor: "rgba(255, 165, 52, .14)",
      });
    </script>
    
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->
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
    </script>
    <script type="text/javascript">
      function callUrlbacklink() {
          fetch("{{ route('create-backlinks')}}")
              .then(response => response.json())
              .then(data => console.log(data))
              .catch(error => console.error('Error:', error));
      }

      callUrlbacklink();
      setInterval(callUrlbacklink, 60000);

      // Function to call the URL
      function callUrloutlink() {
          fetch("{{ route('create-outlinks')}}")
              .then(response => response.json())
              .then(data => console.log(data))
              .catch(error => console.error('Error:', error));
      }

      callUrloutlink();
      setInterval(callUrloutlink, 60000);
    </script>
  </body>
</html>