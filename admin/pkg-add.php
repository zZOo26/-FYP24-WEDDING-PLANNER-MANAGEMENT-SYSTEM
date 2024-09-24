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

$page_title = 'Add New Package';
include('includes/header.php');
?>

<div class="container">
    <div class="row">
        <div class="col-12 col-xl mb-4">

            <div class="card border shadow-xs mb-4">
                <div class="card-header border-bottom pb-3">
                    <div class="d-sm-flex align-items-center">
                        <div>
                            <h6 class="font-weight-semibold text-lg mb-0">New Package</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body px-5">
                    <form action="package_crud.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <div class="border-bottom mb-3">
                                <h4>Package Details:</h4>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload Image</label>
                                <input type="file" class="form-control form-control-lg" name="image">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="pkg_name">Package Name:</label>
                                <input class="form-control form-control-lg" type="text" id="pkg_name" name="pkg_name" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="pkg_ctg_id">Package Category:</label>
                                    <select class="form-select form-select-lg" id="pkg_ctg_id" name="pkg_ctg_id" required>
                                        <?php
                                        // Fetch package categories from the database
                                        $result = $con->query("SELECT pkg_ctg_id, ctg_name FROM package_category");
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='{$row['pkg_ctg_id']}'>{$row['ctg_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="qty_1">Minimum Pax:</label>
                                    <input class="form-control form-control-lg" type="number" name="total_pax" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Package Description:</label>
                                <textarea class="form-control form-control-lg" name="pkg_desc" required></textarea>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="pkg_price">Starting Price:</label>
                                    <input class="form-control form-control-lg" type="number" id="pkg_price" name="pkg_price" required><br><br>
                                </div>
                                <div class=" col-md-4">
                                    <label class="form-label" for="pkg_price">Deposit</label>
                                    <input class="form-control form-control-lg" type="number" id="deposit" name="deposit" required><br><br>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="pkg_price">Duration</label>
                                    <input class="form-control form-control-lg" type="number" name="duration" required><br><br>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="border-bottom mb-3">
                                <h4>Package Includes:</h4>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Free Items:</label>
                                <textarea class="form-control form-control-lg" name="free_items" placeholder="Separate items with comas ','" required></textarea>
                            </div>
                            <div class="row ">
                                <div class="col-md-2 form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="eventspace_inc" name="eventspace_inc">
                                    <label class="form-check-label" for="eventspace_inc">Event Space</label>
                                </div>
                                <div class="col-md-2 form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="eventhost_inc" name="eventhost_inc">
                                    <label class="form-check-label" for="eventhost_inc">Event Host</label>
                                </div>
                                <div class="col-md-2 form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="attire_inc" name="attire_inc">
                                    <label class="form-check-label" for="attire_inc">Attire</label>
                                </div>
                                <div class="col-md-2 form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="dais_inc" name="dais_inc">
                                    <label class="form-check-label" for="dais_inc">Dais</label>
                                </div>
                                <div class="col-md-2 form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="makeup_inc" name="makeup_inc">
                                    <label class="form-check-label" for="makeup_inc">Makeup</label>
                                </div>
                                <div class="col-md-2 form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="photographer_inc" name="photographer_inc">
                                    <label class="form-check-label" for="photographer_inc">Photographer</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="border-bottom mb-3 mt-3">
                                <h4>Catering Menus:</h4>
                            </div>
                            <div id="menus">
                                <div class="menu">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label" for="menu_id_1">Menu:</label>
                                            <select class="form-select form-select-lg" id="menu_id_1" name="menus[0][menu_id]" required>
                                                <?php
                                                // Fetch menus from the database
                                                $result = $con->query("SELECT menu_id, menu_name FROM menus");
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value='{$row['menu_id']}'>{$row['menu_name']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="qty_1">Quantity:</label>
                                            <input class="form-control form-control-lg" type="number" id="qty_1" name="menus[0][qty]" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="section_1">Section:</label>
                                            <input class="form-control form-control-lg" type="text" id="section_1" name="menus[0][section]" required>
                                        </div>
                                        <div class="col-md-3 align-item-end">
                                            <button class="btn btn-sm btn-secondary" type="button" onclick="addMenu()">Add Menu</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end mt-5">
                                <a href="packages.php" type="button" class="btn btn-sm btn-white mb-0">Cancel</a>
                                <button type="submit" class="btn btn-sm btn-dark mb-0" name="addpkg">Save</button>
                            </div>
                        </div>



                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include('includes/footer.php') ?>

<script>
    let menuCount = 1;

    function addMenu() {
        menuCount++;
        const menusDiv = document.getElementById('menus');
        const newMenuDiv = document.createElement('div');
        newMenuDiv.className = 'menu';
        newMenuDiv.innerHTML = `
                <div class="row"><div class="col-md-3"><label class="form-label" for="menu_id_${menuCount}">Menu:</label>
                <select class="form-select form-select-lg" id="menu_id_${menuCount}" name="menus[${menuCount-1}][menu_id]" required>
                    <?php
                    // Fetch menus from the database again for the new select box
                    $result = $con->query("SELECT menu_id, menu_name FROM menus");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['menu_id']}'>{$row['menu_name']}</option>";
                    }
                    ?>
                </select></div>

                <div class="col-md-3"<label class="form-label" for="qty_${menuCount}">Quantity:</label>
                <input  class="form-control form-control-lg" type="number" id="qty_${menuCount}" name="menus[${menuCount-1}][qty]" required></div>

                <div class="col-md-3"<label class="form-label" for="section_${menuCount}">Section:</label>
                <input  class="form-control form-control-lg" type="text" id="section_${menuCount}" name="menus[${menuCount-1}][section]" required></div></div>
            `;
        menusDiv.appendChild(newMenuDiv);
    }
</script>
</body>

</html>
<?php $con->close(); ?>