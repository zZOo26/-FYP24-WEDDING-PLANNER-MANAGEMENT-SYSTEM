<?php
session_start();
include('../dbcon.php');
include('./functions.php');

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
  // Redirect to login page if not logged in
  header("Location: admin_login.php");
  die;
}


$user_data = check_login($con);

$_SESSION;
$page_title = 'Dashboard';
include('includes/header.php');

// Fetch the sales data for the current year
$current_year = date('Y');
$sales_data = getMonthlySales($current_year);
$total_sales = array_sum(array_column($sales_data, 'total_sales'));

// Fetch additional metrics
$total_customers = getTotalCustomers();
$total_packages = getTotalPackages();
$total_bridal_attires = getTotalBridalAttires();
$total_bridal_dais = getTotalBridalDais();
$total_dais_rental = getTotalDaisRental();
$total_attire_rental = getTotalAttireRental();
$total_package_booking = getTotalPackageBooking();

// Fetch total expenses and monthly expenses
$total_expenses = getTotalExpenses();

$year = date('Y'); // Current year
$monthly_expenses_data = getMonthlyExpenses($year);


// Extract the expense data for the current month
$current_month = date('Y-m'); // Current month in 'YYYY-MM' format
$current_month_expense = 0;

foreach ($monthly_expenses_data as $expense) {
  if ($expense['month'] == $current_month) {
    $current_month_expense = $expense['monthly_expense'];
    break;
  }
}

?>

<div class="row">
  <!-- Monthly Sales Card -->
  <div class="col-xl-3 col-sm-6 mb-xl-0">
    <div class="card border shadow-xs mb-4">
      <div class="card-body text-start p-3 w-100">
        <div class="icon icon-shape icon-sm bg-dark text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
          <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z" />
            <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="w-100">
              <p class="text-sm text-secondary mb-1">Monthly Sales</p>
              <h4 class="mb-2 font-weight-bold" id="monthly-sales">RM0.00</h4>
              <div class="d-flex align-items-center">
                <span class="text-sm text-success font-weight-bolder">
                  <i class="fa fa-chevron-up text-xs me-1"></i><span id="monthly-sales-percentage">0%</span>
                </span>
                <span class="text-sm ms-1">from RM<span id="previous-month-sales">0.00</span></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Sales Card -->
  <div class="col-xl-3 col-sm-6 mb-xl-0">
    <div class="card border shadow-xs mb-4">
      <div class="card-body text-start p-3 w-100">
        <div class="icon icon-shape icon-sm bg-dark text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
          <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6zm4.5 7.5a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0v-2.25a.75.75 0 01.75-.75zm3.75-1.5a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0V12zm2.25-3a.75.75 0 01.75.75v6.75a.75.75 0 01-1.5 0V9.75A.75.75 0 0113.5 9zm3.75-1.5a.75.75 0 00-1.5 0v9a.75.75 0 001.5 0v-9z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="w-100">
              <p class="text-sm text-secondary mb-1">Total Sales</p>
              <h4 class="mb-2 font-weight-bold" id="total-sales">RM<?php echo number_format($total_sales, 2); ?></h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Monthly Expenses Card -->
  <div class="col-xl-3 col-sm-6 mb-xl-0">
    <div class="card border shadow-xs mb-4">
      <div class="card-body text-start p-3 w-100">
        <div class="icon icon-shape icon-sm bg-dark text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
          <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6zm4.5 7.5a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0v-2.25a.75.75 0 01.75-.75zm3.75-1.5a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0V12zm2.25-3a.75.75 0 01.75.75v6.75a.75.75 0 01-1.5 0V9.75A.75.75 0 0113.5 9zm3.75-1.5a.75.75 0 00-1.5 0v9a.75.75 0 001.5 0v-9z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="w-100">
              <p class="text-sm text-secondary mb-1">Monthly Expenses</p>
              <h4 class="mb-2 font-weight-bold" id="total-monthly-expenses">RM<?php echo is_numeric($current_month_expense) ? number_format($current_month_expense, 2) : '0.00'; ?></h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Expenses Card -->
  <div class="col-xl-3 col-sm-6 mb-xl-0">
    <div class="card border shadow-xs mb-4">
      <div class="card-body text-start p-3 w-100">
        <div class="icon icon-shape icon-sm bg-dark text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
          <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6zm4.5 7.5a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0v-2.25a.75.75 0 01.75-.75zm3.75-1.5a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0V12zm2.25-3a.75.75 0 01.75.75v6.75a.75.75 0 01-1.5 0V9.75A.75.75 0 0113.5 9zm3.75-1.5a.75.75 0 00-1.5 0v9a.75.75 0 001.5 0v-9z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="w-100">
              <p class="text-sm text-secondary mb-1">Total Expenses</p>
              <h4 class="mb-2 font-weight-bold" id="total-expenses">RM<?php echo is_numeric($total_expenses) ? number_format($total_expenses, 2) : '0.00'; ?></h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<div class="row">
  <!-- Total Package booking Card -->
  <div class="col-xl-4 col-sm-6 mb-xl-0">
    <div class="card border shadow-xs mb-4">
      <div class="card-body text-start p-3 w-100">
        <div class="icon icon-shape icon-sm bg-dark text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
          <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="d-block me-2">
            <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z" />
          </svg>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="w-100">
              <p class="text-sm text-secondary mb-1">Total Package Bookings</p>
              <h4 class="mb-2 font-weight-bold" id="total-pakage-bookings"><?php echo $total_package_booking; ?></h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Total Packages Card -->
  <div class="col-xl-4 col-sm-6 mb-xl-0">
    <div class="card border shadow-xs mb-4">
      <div class="card-body text-start p-3 w-100">
        <div class="icon icon-shape icon-sm bg-dark text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
          <i class="fas fa-box " style="font-size: 16px;"></i>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="w-100">
              <p class="text-sm text-secondary mb-1">Total Dais Rentals</p>
              <h4 class="mb-2 font-weight-bold" id="total-packages"><?php echo $total_dais_rental; ?></h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Bridal Attires Card -->
  <div class="col-xl-4 col-sm-6 mb-xl-0">
    <div class="card border shadow-xs mb-4">
      <div class="card-body text-start p-3 w-100">
        <div class="icon icon-shape icon-sm bg-dark text-white text-center border-radius-sm d-flex align-items-center justify-content-center mb-3">
          <i class="fa-solid fa-person-half-dress " style="font-size: 16px;"></i>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="w-100">
              <p class="text-sm text-secondary mb-1">Total Attire Rentals</p>
              <h4 class="mb-2 font-weight-bold" id="total-bridal-attires"><?php echo $total_attire_rental; ?></h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>




<!-- Chart Container -->
<div class="row">
  <div class="col-12">
    <canvas id="monthlySalesChart"></canvas>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Sales data fetched from PHP
    const salesData = <?php echo json_encode($sales_data); ?>;

    // Monthly Expenses data fetched from PHP
    const expensesData = <?php echo json_encode($monthly_expenses_data); ?>;

    // Prepare data for Chart.js
    const labels = salesData.map(data => data.month);
    const sales = salesData.map(data => parseFloat(data.total_sales));
    const expenses = expensesData.map(data => parseFloat(data.monthly_expense));

    // Update Monthly Sales Card
    const latestSales = sales[sales.length - 1];
    const previousSales = sales[sales.length - 2] || 0;
    const salesPercentage = ((latestSales - previousSales) / previousSales * 100).toFixed(2);

    document.getElementById('monthly-sales').textContent = `RM${latestSales.toFixed(2)}`;
    document.getElementById('monthly-sales-percentage').textContent = `${salesPercentage}%`;
    document.getElementById('previous-month-sales').textContent = `${previousSales.toFixed(2)}`;

    // Create the chart
    const ctx = document.getElementById('monthlySalesChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Monthly Sales',
          data: sales,
          borderColor: 'rgba(75, 192, 192, 1)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          fill: true,
          tension: 0.1
        }, {
          label: 'Monthly Expenses',
          data: expenses,
          borderColor: 'rgba(255, 99, 132, 1)', // Red color for expenses
          backgroundColor: 'rgba(255, 99, 132, 0.2)',
          fill: true,
          tension: 0.1
        }]
      },
      options: {
        responsive: true,
        scales: {
          x: {
            beginAtZero: true
          },
          y: {
            beginAtZero: true
          }
        }
      }
    });
  });
</script>





<?php include('includes/footer.php'); ?>