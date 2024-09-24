<footer class="footer pt-3  ">
  <div class="container-fluid">
    <div class="row align-items-center justify-content-lg-between">
      <div class="col-lg-6 mb-lg-0 mb-4">
        <div class="copyright text-center text-xs text-muted text-lg-start">
          Copyright
          © <script>
            document.write(new Date().getFullYear())
          </script>
          <a href="../home.php" class="text-secondary" target="_blank">Waniey Bridal Wedding Planner Ent</a>. |
          All Right Reserved.
        </div>
      </div>
      <div class="col-lg-6">
        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
          <li class="nav-item">
            <a href="../index.php" class="nav-link text-xs text-muted" target="_blank">Waniey Bridal</a>
          </li>
          <li class="nav-item">
            <a href="../about.php" class="nav-link text-xs text-muted" target="_blank">About Us</a>
          </li>
          <li class="nav-item">
            <a href="../t&c.php" class="nav-link text-xs text-muted" target="_blank">Terms & Conditions</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</footer>
</div>
</main>

<!--   Core JS Files   -->
<script src="./assets/js/core/popper.min.js"></script>
<script src="./assets/js/core/bootstrap.min.js"></script>
<script src="./assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="./assets/js/plugins/smooth-scrollbar.min.js"></script>
<script src="./assets/js/plugins/chartjs.min.js"></script>
<script src="./assets/js/plugins/swiper-bundle.min.js" type="text/javascript"></script>
<script>
  if (document.getElementsByClassName('mySwiper')) {
    var swiper = new Swiper(".mySwiper", {
      effect: "cards",
      grabCursor: true,
      initialSlide: 1,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
  };


  var ctx = document.getElementById("chart-bars").getContext("2d");

  new Chart(ctx, {
    type: "bar",
    data: {
      labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"],
      datasets: [{
          label: "Sales",
          tension: 0.4,
          borderWidth: 0,
          borderSkipped: false,
          backgroundColor: "#2ca8ff",
          data: [450, 200, 100, 220, 500, 100, 400, 230, 500, 200],
          maxBarThickness: 6
        },
        {
          label: "Sales",
          tension: 0.4,
          borderWidth: 0,
          borderSkipped: false,
          backgroundColor: "#7c3aed",
          data: [200, 300, 200, 420, 400, 200, 300, 430, 400, 300],
          maxBarThickness: 6
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          backgroundColor: '#fff',
          titleColor: '#1e293b',
          bodyColor: '#1e293b',
          borderColor: '#e9ecef',
          borderWidth: 1,
          usePointStyle: true
        }
      },
      interaction: {
        intersect: false,
        mode: 'index',
      },
      scales: {
        y: {
          stacked: true,
          grid: {
            drawBorder: false,
            display: true,
            drawOnChartArea: true,
            drawTicks: false,
            borderDash: [4, 4],
          },
          ticks: {
            beginAtZero: true,
            padding: 10,
            font: {
              size: 12,
              family: "Noto Sans",
              style: 'normal',
              lineHeight: 2
            },
            color: "#64748B"
          },
        },
        x: {
          stacked: true,
          grid: {
            drawBorder: false,
            display: false,
            drawOnChartArea: false,
            drawTicks: false
          },
          ticks: {
            font: {
              size: 12,
              family: "Noto Sans",
              style: 'normal',
              lineHeight: 2
            },
            color: "#64748B"
          },
        },
      },
    },
  });


  var ctx2 = document.getElementById("chart-line").getContext("2d");

  var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

  gradientStroke1.addColorStop(1, 'rgba(45,168,255,0.2)');
  gradientStroke1.addColorStop(0.2, 'rgba(45,168,255,0.0)');
  gradientStroke1.addColorStop(0, 'rgba(45,168,255,0)'); //blue colors

  var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

  gradientStroke2.addColorStop(1, 'rgba(119,77,211,0.4)');
  gradientStroke2.addColorStop(0.7, 'rgba(119,77,211,0.1)');
  gradientStroke2.addColorStop(0, 'rgba(119,77,211,0)'); //purple colors

  new Chart(ctx2, {
    plugins: [{
      beforeInit(chart) {
        const originalFit = chart.legend.fit;
        chart.legend.fit = function fit() {
          originalFit.bind(chart.legend)();
          this.height += 40;
        }
      },
    }],
    type: "line",
    data: {
      labels: ["Aug 18", "Aug 19", "Aug 20", "Aug 21", "Aug 22", "Aug 23", "Aug 24", "Aug 25", "Aug 26", "Aug 27", "Aug 28", "Aug 29", "Aug 30", "Aug 31", "Sept 01", "Sept 02", "Sept 03", "Aug 22", "Sept 04", "Sept 05", "Sept 06", "Sept 07", "Sept 08", "Sept 09"],
      datasets: [{
          label: "Volume",
          tension: 0,
          borderWidth: 2,
          pointRadius: 3,
          borderColor: "#2ca8ff",
          pointBorderColor: '#2ca8ff',
          pointBackgroundColor: '#2ca8ff',
          backgroundColor: gradientStroke1,
          fill: true,
          data: [2828, 1291, 3360, 3223, 1630, 980, 2059, 3092, 1831, 1842, 1902, 1478, 1123, 2444, 2636, 2593, 2885, 1764, 898, 1356, 2573, 3382, 2858, 4228],
          maxBarThickness: 6

        },
        {
          label: "Trade",
          tension: 0,
          borderWidth: 2,
          pointRadius: 3,
          borderColor: "#832bf9",
          pointBorderColor: '#832bf9',
          pointBackgroundColor: '#832bf9',
          backgroundColor: gradientStroke2,
          fill: true,
          data: [2797, 2182, 1069, 2098, 3309, 3881, 2059, 3239, 6215, 2185, 2115, 5430, 4648, 2444, 2161, 3018, 1153, 1068, 2192, 1152, 2129, 1396, 2067, 1215, 712, 2462, 1669, 2360, 2787, 861],
          maxBarThickness: 6
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'top',
          align: 'end',
          labels: {
            boxWidth: 6,
            boxHeight: 6,
            padding: 20,
            pointStyle: 'circle',
            borderRadius: 50,
            usePointStyle: true,
            font: {
              weight: 400,
            },
          },
        },
        tooltip: {
          backgroundColor: '#fff',
          titleColor: '#1e293b',
          bodyColor: '#1e293b',
          borderColor: '#e9ecef',
          borderWidth: 1,
          pointRadius: 2,
          usePointStyle: true,
          boxWidth: 8,
        }
      },
      interaction: {
        intersect: false,
        mode: 'index',
      },
      scales: {
        y: {
          grid: {
            drawBorder: false,
            display: true,
            drawOnChartArea: true,
            drawTicks: false,
            borderDash: [4, 4]
          },
          ticks: {
            callback: function(value, index, ticks) {
              return parseInt(value).toLocaleString() + ' EUR';
            },
            display: true,
            padding: 10,
            color: '#b2b9bf',
            font: {
              size: 12,
              family: "Noto Sans",
              style: 'normal',
              lineHeight: 2
            },
            color: "#64748B"
          }
        },
        x: {
          grid: {
            drawBorder: false,
            display: false,
            drawOnChartArea: false,
            drawTicks: false,
            borderDash: [4, 4]
          },
          ticks: {
            display: true,
            color: '#b2b9bf',
            padding: 20,
            font: {
              size: 12,
              family: "Noto Sans",
              style: 'normal',
              lineHeight: 2
            },
            color: "#64748B"
          }
        },
      },
    },
  });
</script>
<script>
  var win = navigator.platform.indexOf('Win') > -1;
  if (win && document.querySelector('#sidenav-scrollbar')) {
    var options = {
      damping: '0.5'
    }
    Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
  }
</script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ensure the dropdown stays open when an item is clicked
        document.addEventListener('DOMContentLoaded', function() {
            var dropdownToggles = document.querySelectorAll('.dropdown-toggle');

            dropdownToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function() {
                    var target = document.querySelector(toggle.getAttribute('href'));
                    var expanded = toggle.getAttribute('aria-expanded') === 'true';

                    if (!expanded) {
                        target.classList.add('show');
                        toggle.setAttribute('aria-expanded', 'true');
                    }
                });
            });
        });
    </script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Corporate UI Dashboard: parallax effects, scripts for the example pages etc -->
<script src="./assets/js/corporate-ui-dashboard.min.js?v=1.0.0"></script>
</body>

</html>