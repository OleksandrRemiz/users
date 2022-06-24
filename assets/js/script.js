function showModalWindow(title, message){
  $('#modalWindowLabel').text(title);
  $('#modalWindowMessage').text(message);
  $('#modalWindow').modal('show');
}

function confirmWindow(title, message){
  $('#confirmWindow').modal({
    keyboard: false,
    backdrop: "static"
  });
  $('#confirmWindowLabel').text(title);
  $('#confirmWindowMessage').text(message);
  $('#confirmWindow').modal('show');
}

function createTablerow(id, firstname, lastname, role_id, role, status){
  let tablerow = `<tr data-row-id=` + id + `>
    <td class="align-middle">
      <input type="checkbox" class="styled-checkbox user-checkbox" id="checkbox-` + id + `">
      <label for="checkbox-` + id + `"></label>
    </td>
    <td class="text-nowrap align-middle">
      <span class="firstname">` + firstname + `</span>
      <span class="lastname">` + lastname + `</span>
    </td>
    <td class="text-nowrap align-middle">
      <span class="role-span">` + role + `</span>
    </td>
    <td class="text-center align-middle status-td">`;

    if(Number(status)) tablerow += `<i class="fa fa-circle active-circle"></i>`;
    if(!Number(status)) tablerow += `<i class="fa fa-circle not-active-circle"></i>`;

    tablerow += `</td>
    <td class="text-center align-middle">
      <div class="btn-group align-top">
        <button class="btn btn-sm btn-outline-secondary badge edit-button" type="button" data-toggle="modal" data-target="#user-form-modal" editor-number="` + id + `">Edit</button>
        <button class="btn btn-sm btn-outline-secondary badge delete-button" type="button"><i class="fa fa-trash"></i></button>
      </div>
    </td>
  </tr>`;
  return tablerow;
}

// Edit button preparing fields
$("#users-table").on("click", ".edit-button", function (){
  // Set action to be done
  $("#action-hidden").val("edit");
  // Id of user to be changed
  id = $(this).closest("tr").attr("data-row-id");
  $("#to-edit-hidden").val(id);
  $.ajax({
    type: "POST",
    url: "../../app/get_user.php",
    data: {id:id}
  }).done(function(response){
    response = JSON.parse(response);
    if(response["code"]){
      $("#first-name").val(response["firstname"]);
      $("#last-name").val(response["lastname"]);
      $("#role option").removeAttr("selected");
      let selectedRoleOption = '[value = ' + response["roleid"] + ']'
      $(selectedRoleOption).prop('selected', true);
      $("#checkbox-google-input").removeAttr("checked");
      if(response["status"] == "0") $('#checkbox-status').prop("checked", false);
      if(response["status"] == "1") $('#checkbox-status').prop("checked", true);
    }else{
      showModalWindow("Warning!", response["error"]["message"]);
    }
  });
});

// Add button preparing fields
$(".add-button").click(function (){
  $("#action-hidden").val("add");
  $("#first-name").val("");
  $("#last-name").val("");
});

// Add & update user
$("#btn-save-modal").click(function (){
  firstname = $("#first-name").val();
  lastname = $("#last-name").val();
  id = $("#to-edit-hidden").val();
  role_id = $("#role").val();
  user_status = Number($('#checkbox-status').is(':checked'));

  if($("#action-hidden").val() == "edit"){
    $.ajax({
      type: "POST",
      url: "../../app/update_user.php",
      data: {firstname:firstname, lastname:lastname, id:id, role_id:role_id, status:user_status}
    }).done(function(response) {
      response = JSON.parse(response);
      if(response["status"]){
        $("[data-row-id=" + id + "] .firstname").text(firstname);
        $("[data-row-id=" + id + "] .lastname").text(lastname);
        $("[data-row-id=" + id + "] .role-span").text(response["role"]);
        if(user_status){
          $("[data-row-id=" + id + "] .fa-circle").removeClass("not-active-circle");
          $("[data-row-id=" + id + "] .fa-circle").addClass("active-circle");
        }else if(!user_status){
          $("[data-row-id=" + id + "] .fa-circle").removeClass("active-circle");
          $("[data-row-id=" + id + "] .fa-circle").addClass("not-active-circle");
        }
      }else{
        showModalWindow("Warning!", response["error"]["message"]);
      }
    });
  }

  if($("#action-hidden").val() == "add"){
    $.ajax({
      type: "POST",
      url: "../../app/add_user.php",
      data: {firstname:firstname, lastname:lastname, role_id:role_id, status:status}
    }).done(function(response){
      response = JSON.parse(response);
      if(response["status"]){
        let row = createTablerow(response["user_id"], firstname, lastname, role_id, response["role"], status);
        $("#users-table tbody:last-child").append(row);
      }else{
        showModalWindow("Warning!", response["error"]["message"]);
      }
    });
  }
});

// Deleting user
$("#users-table").on("click", ".delete-button", function (){
  let id = $(this).closest("tr").attr("data-row-id");
  //let result = confirm('Sure to delete this user?');
  confirmWindow("Delete?", "Sure to delete this user?");
  $('#yes-button').click(function(){
    $.ajax({
      type: "POST",
      url: "../../app/delete_user.php",
      data: {id:id}
    }).done(function(response) {
      response = JSON.parse(response);
      if(response["status"]){
        let rowAttr = '[row-id-' + id + ']';
        $("[data-row-id=" + id + "]").remove();
      }else{
        showModalWindow("Warning!", response["error"]["message"]);
      }
    });
  });
});

// Main checkbox
$("#all-items").click(function(){
  let prop = $(this).prop('checked');
  if(prop) $(".user-checkbox").prop("checked", true);
  if(!prop) $(".user-checkbox").prop("checked", false);
});

// Checkboxes
$("#users-table").on("click", ".user-checkbox", function (){
  let falseCounter = 0;
  $(".user-checkbox").each(function(){
    if(!$(this).is(':checked')) falseCounter++;
  });
  if(falseCounter == 0){
    $("#all-items").prop("checked", true);
  }else{
    $("#all-items").prop("checked", false);
  }
});

// Set users active/inactive/delete
function usersMultiaction(arrayOfUsers, action){
  $.ajax({
    type: "POST",
    url: "../../app/multiaction.php",
    data: {action:action, users:arrayOfUsers}
  }).done(function(response) {
    response = JSON.parse(response);
    if(response["status"]){
      arrayOfUsers.forEach(function(item) {
        if(action == "activate"){
          $("[data-row-id=" + item + "] .fa-circle").removeClass("not-active-circle");
          $("[data-row-id=" + item + "] .fa-circle").addClass("active-circle");
        }else if(action == "inactivate"){
          $("[data-row-id=" + item + "] .fa-circle").removeClass("active-circle");
          $("[data-row-id=" + item + "] .fa-circle").addClass("not-active-circle");
        }else if(action == "delete"){
          $("[data-row-id=" + item + "]").remove();
        }
      });
    }else{
      showModalWindow("Warning!", response["error"]["message"]);
    }
  }); 
}

// Creates array of ids of checked users
function checkedUsersArray(){
  let users = [];
  $(".user-checkbox").each(function(){
    if($(this).is(':checked')){
      users.push($(this).closest("tr").attr("data-row-id"));
    }
  });
  return users;
}

// Users options
$(".users-options-submit").click(function(){
  let action = $(this).siblings("select").val();
  if(action){
    let users = checkedUsersArray();
    if(users.length){
      if(action == "delete"){
        confirmWindow("Sure?", "Make these changes?");
        $('#yes-button').click(function(){
          usersMultiaction(users, action);
        });
      }else{
        usersMultiaction(users, action);
      }
    }else{
      showModalWindow("Warning!", "Please, choose some items.");
    }
  }else{
    showModalWindow("Warning!", "Please, choose an action to do with selected users.");
  }
});
