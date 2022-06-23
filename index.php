<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Users table</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="assets/css/styles.css">
</head>
<?php
require_once "app/db.php";
?>
<body>
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
  <div class="container">
    <div class="row flex-lg-nowrap">
      <div class="col">
        <div class="row flex-lg-nowrap">
          <div class="col mb-3">
            <div class="e-panel card">
              <div class="card-body">
                <div class="card-title">
                  <h6 class="mr-2" id="test-h-id"><span>Users</span></h6>
                </div>
                <div class="e-table">
                  <div class="table-responsive table-lg mt-3">
                    <?php include("edit-block.php"); ?>
                    <table class="table table-bordered" id="users-table">
                      <thead>
                        <tr>
                          <th class="align-top">
                            <div class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0">
                              <input type="checkbox" class="styled-checkbox" id="all-items">
                              <label for="all-items"></label>
                            </div>
                            
                          </th>
                          <th class="max-width">Name</th>
                          <th class="sortable">Role</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $users = R::getAll("SELECT * from users INNER JOIN roles ON users.role_id = roles.role_id");
                          foreach ($users as $user){
                            ?>
                            <tr data-row-id=<?= $user['id']; ?>>
                              <td class="align-middle">
                                <input type="checkbox" class="styled-checkbox user-checkbox" id="checkbox-<?= $user['id']; ?>">
                                <label for="checkbox-<?= $user['id']; ?>"></label>
                              </td>
                              <td class="text-nowrap align-middle">
                                <span class="firstname"><?= $user['firstname']; ?></span>
                                <span class="lastname"><?= $user['lastname']; ?></span>
                              </td>
                              <td class="text-nowrap align-middle">
                                <span class="role-span"><?= $user['role']; ?></span>
                              </td>
                              <td class="text-center align-middle status-td">
                                  <?php
                                    if($user['status'])
                                      echo '<i class="fa fa-circle active-circle">';
                                    else
                                      echo '<i class="fa fa-circle not-active-circle">';
                                  ?>
                                </i>
                              </td>
                              <td class="text-center align-middle">
                                <div class="btn-group align-top">
                                  <button class="btn btn-sm btn-outline-secondary badge edit-button" type="button" data-toggle="modal" data-target="#user-form-modal">Edit</button>
                                  <button class="btn btn-sm btn-outline-secondary badge delete-button" type="button"><i
                                      class="fa fa-trash"></i></button>
                                </div>
                              </td>
                            </tr>
                        <?php
                          }
                        ?>
                      </tbody>
                    </table>
                    <?php include("edit-block.php"); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- User Form Modal -->
        
      <div class="modal fade" id="user-form-modal" tabindex="-1" aria-labelledby="user-form-modal" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="UserModalLabel">User</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form id="modal-form">
                <div class="form-group">
                  <label for="first-name" class="col-form-label">First Name:</label>
                  <input type="text" class="form-control" id="first-name">
                </div>
                <div class="form-group">
                  <label for="last-name" class="col-form-label">Last Name:</label>
                  <input type="text" class="form-control" id="last-name">
                </div>
                <input type="hidden" class="form-control" id="to-edit-hidden">
                <input type="hidden" class="form-control" id="action-hidden">
                <label class="checkbox-google">
                  Status
                  <input type="checkbox" id="checkbox-status">
                  <span class="checkbox-google-switch"></span>
                </label>
                <div class="form-group">
                  <label for="role" class="col-form-label">Role:</label>
                  <select id="role">
                    <?php $roles = R::getAll("SELECT * from roles"); 
                      foreach($roles as $role){
                        echo '<option value="' . $role["role_id"] . '">' . $role["role"] . '</option>';
                      }
                    ?>
                  </select>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="btn-save-modal" data-dismiss="modal">Save</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="modalWindow" tabindex="-1" role="dialog" aria-labelledby="modalWindowLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalWindowLabel"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="modalWindowMessage">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <!--<button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
          </div>
        </div>
      </div>

      <!-- Confirm -->
      <div class="modal fade" id="confirmWindow" tabindex="-1" role="dialog" aria-labelledby="confirmWindowLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="confirmWindowLabel"></h5>
              <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>-->
            </div>
            <div class="modal-body" id="confirmWindowMessage">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" id="yes-button">Yes</button>
              <button type="button" class="btn btn-primary" data-dismiss="modal" id="no-button">NO</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>