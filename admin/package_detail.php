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

// Check if package ID is provided
if (!isset($_GET['pkg_id'])) {
    header("Location: packages.php");
    die;
}

$pkg_id = $_GET['pkg_id'];

// Fetch package details from the database
$query = $con->prepare("SELECT * FROM packages WHERE pkg_id = ?");
$query->bind_param("i", $pkg_id);
$query->execute();
$package = $query->get_result()->fetch_assoc();

if (!$package) {
    header("Location: packages.php");
    die;
}

$page_title = 'View Package';
include('includes/header.php');
?>

<div class="container">
    <div class="row">
        <div class="col-12 col-xl mb-4">
            <div class="card border shadow-xs mb-4">
                <div class="card-header border-bottom pb-3">
                    <div class="d-sm-flex align-items-center">
                        <div>
                            <h6 class="font-weight-semibold text-lg mb-0">View and Update Package</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body px-5">
                    <form action="package_crud.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="pkg_id" value="<?php echo $package['pkg_id']; ?>">
                        <div class="mb-3">
                            <div class="border-bottom mb-3">
                                <h4>Package Details:</h4>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2"><img src="uploads/<?php echo $package['pkg_img']; ?>" alt="Package Image" width="100"></div>
                                <div class="col-md-10">
                                    <label class="form-label">Upload Image</label>
                                    <input type="file" class="form-control form-control-lg" name="image">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="pkg_name">Package Name:</label>
                                <input class="form-control form-control-lg" type="text" id="pkg_name" name="pkg_name" value="<?php echo $package['pkg_name']; ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="pkg_ctg_id">Package Category:</label>
                                    <select class="form-select form-select-lg" id="pkg_ctg_id" name="pkg_ctg_id" required>
                                        <?php
                                        $result = $con->query("SELECT pkg_ctg_id, ctg_name FROM package_category");
                                        while ($row = $result->fetch_assoc()) {
                                            $selected = $row['pkg_ctg_id'] == $package['pkg_ctg_id'] ? 'selected' : '';
                                            echo "<option value='{$row['pkg_ctg_id']}' {$selected}>{$row['ctg_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="qty_1">Minimum Pax:</label>
                                    <input class="form-control form-control-lg" type="number" name="total_pax" value="<?php echo $package['total_pax']; ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Package Description:</label>
                                <textarea class="form-control form-control-lg" name="pkg_desc" required><?php echo $package['pkg_desc']; ?></textarea>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="pkg_price">Starting Price:</label>
                                    <input class="form-control form-control-lg" type="number" id="pkg_price" name="pkg_price" value="<?php echo $package['pkg_price']; ?>" required><br><br>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="pkg_price">Deposit</label>
                                    <input class="form-control form-control-lg" type="number" id="deposit" name="deposit" value="<?php echo $package['deposit']; ?>" required><br><br>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="pkg_price">Duration</label>
                                    <input class="form-control form-control-lg" type="number" name="duration" value="<?php echo $package['duration']; ?>" required><br><br>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="border-bottom mb-3">
                                <h4>Package Includes:</h4>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="eventspace_inc" name="eventspace_inc" <?php echo $package['eventspace_inc'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="eventspace_inc">Event Space</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="eventhost_inc" name="eventhost_inc" <?php echo $package['eventhost_inc'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="eventhost_inc">Event Host</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="attire_inc" name="attire_inc" <?php echo $package['attire_inc'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="attire_inc">Attire</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="dais_inc" name="dais_inc" <?php echo $package['dais_inc'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="dais_inc">Dais</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="makeup_inc" name="makeup_inc" <?php echo $package['makeup_inc'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="makeup_inc">Makeup</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="photographer_inc" name="photographer_inc" <?php echo $package['photographer_inc'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="photographer_inc">Photographer</label>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="free_items">Free Items:</label>
                                <input class="form-control form-control-lg" type="text" id="free_items" name="free_items" value="<?php echo $package['free_items']; ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="border-bottom mb-3">
                                <h4>Menus:</h4>
                            </div>
                            <div id="menus">
                                <?php
                                $menu_count = 0;
                                $result = $con->query("SELECT * FROM package_menu WHERE pkg_id = $pkg_id");
                                while ($menu = $result->fetch_assoc()) {
                                    $menu_count++;
                                ?>
                                    <div class="menu mb-3" id="menu_<?php echo $menu_count; ?>">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label" for="menu_id_<?php echo $menu_count; ?>">Menu:</label>
                                                <select class="form-select form-select-lg" id="menu_id_<?php echo $menu_count; ?>" name="menus[<?php echo $menu_count - 1; ?>][menu_id]" required>
                                                    <?php
                                                    $menu_result = $con->query("SELECT menu_id, menu_name FROM menus");
                                                    while ($menu_row = $menu_result->fetch_assoc()) {
                                                        $selected = $menu_row['menu_id'] == $menu['menu_id'] ? 'selected' : '';
                                                        echo "<option value='{$menu_row['menu_id']}' {$selected}>{$menu_row['menu_name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label" for="qty_<?php echo $menu_count; ?>">Quantity:</label>
                                                <input class="form-control form-control-lg" type="number" id="qty_<?php echo $menu_count; ?>" name="menus[<?php echo $menu_count - 1; ?>][qty]" value="<?php echo $menu['qty']; ?>" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label" for="section_<?php echo $menu_count; ?>">Section:</label>
                                                <input class="form-control form-control-lg" type="text" id="section_<?php echo $menu_count; ?>" name="menus[<?php echo $menu_count - 1; ?>][section]" value="<?php echo $menu['section']; ?>" required>
                                            </div>
                                            <div class="col-md-3 align-item-end">
                                                <button type="button" class="btn btn-lg btn-danger" onclick="removeMenu(<?php echo $menu['menu_id']; ?>, <?php echo $menu_count; ?>)">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <button type="button" class="btn btn-lg btn-dark" onclick="addMenu()">Add Menu</button>
                        </div>
                        <div class="modal-footer mt-5">
                            <a href="packages.php" type="button" class="btn btn-sm btn-white mb-0">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-dark mb-0" name="updatepkg">Save</button>
                        </div>
                    </form>

                    <script>
                        let menuCount = <?php echo $menu_count; ?>;

                        function addMenu() {
                            menuCount++;
                            const menusDiv = document.getElementById('menus');
                            const newMenuDiv = document.createElement('div');
                            newMenuDiv.className = 'menu mb-3';
                            newMenuDiv.id = `menu_${menuCount}`;
                            newMenuDiv.innerHTML = `
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label" for="menu_id_${menuCount}">Menu:</label>
                    <select class="form-select form-select-lg" id="menu_id_${menuCount}" name="menus[${menuCount-1}][menu_id]" required>
                        <?php
                        $result = $con->query("SELECT menu_id, menu_name FROM menus");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['menu_id']}'>{$row['menu_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="qty_${menuCount}">Quantity:</label>
                    <input class="form-control form-control-lg" type="number" id="qty_${menuCount}" name="menus[${menuCount-1}][qty]" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="section_${menuCount}">Section:</label>
                    <input class="form-control form-control-lg" type="text" id="section_${menuCount}" name="menus[${menuCount-1}][section]" required>
                </div>
                <div class="col-md-3 align-item-end">
                    <button type="button" class="btn btn-lg btn-danger" onclick="removeMenu(${menuCount})">Remove</button>
                </div>
            </div>
        `;
                            menusDiv.appendChild(newMenuDiv);
                        }

                        function removeMenu(menu_id, menuCount) {
                            // Create a hidden input to store the ID of the menu to be removed
                            const removeInput = document.createElement('input');
                            removeInput.type = 'hidden';
                            removeInput.name = 'remove_menus[]';
                            removeInput.value = menu_id;

                            // Append the hidden input to the form
                            document.querySelector('form').appendChild(removeInput);

                            // Remove the menu div
                            document.getElementById(`menu_${menuCount}`).remove();
                        }
                    </script>

                    
                    <?php $con->close(); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>