// currentLocat=window.location.href;
// var url = new URL(currentLocat);
// var view = url.searchParams.get("edit_quotation_id");

$(document).ready(function () {
  console.log("Initializing DataTables with Excel export...");
  
  // Small delay to ensure all DataTables extensions are loaded
  setTimeout(function() {
    console.log("DataTables Buttons available:", typeof $.fn.dataTable.Buttons);
    console.log("Found dataTable elements:", $(".dataTable").length);
    console.log("jQuery version:", $.fn.jquery);
    console.log("DataTables version:", $.fn.dataTable.version);
    console.log("Buttons extension loaded:", typeof $.fn.dataTable.Buttons !== 'undefined');
    
    // Initialize DataTables with Excel export for generic tables, but skip product_tb (server-side init on page)
    $(".dataTable").not("#view_purchase_tb, #product_tb").DataTable({
    autoWidth: true,
    lengthMenu: [
      [10, 20, 50, -1],
      [10, 20, 50, "All"],
    ],
    order: [
      [0, "desc"]
    ],
    dom: 'Bfrtip',
    buttons: [
      {
        extend: 'excel',
        text: 'Export to Excel',
        className: 'btn btn-success btn-sm',
        exportOptions: {
          columns: ':visible'
        }
      }
    ],
    pageLength: 10,
    responsive: true
  });
  

  
  // Initialize DataTables with Excel export for credit_order class
  $(".credit_order").DataTable({
    autoWidth: true,
    lengthMenu: [
      [10, 20, 50, -1],
      [10, 20, 50, "All"],
    ],
    order: [
      [6, "asc"]
    ],
    dom: 'Bfrtip',
    buttons: [
      {
        extend: 'excel',
        text: 'Export to Excel',
        className: 'btn btn-success btn-sm'
      }
    ]
  });
  $(".searchableSelect").select2({
    theme: "bootstrap4",
  });
  $(".selectMulti").select2({
    multiple: true,
    theme: "bootstrap4",
  });
  // $('.my-colorpicker2').colorpicker();

  //  $('.my-colorpicker2').on('colorpickerChange', function(event) {
  //    $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
  //  });

  $("#post_list").sortable({
    placeholder: "ui-state-highlight",
    update: function (event, ui) {
      var post_order_ids = new Array();
      $("#post_list li").each(function () {
        post_order_ids.push($(this).data("post-id"));
      });
      $.ajax({
        url: "php_action/panel.php",
        method: "POST",
        data: {
          sortable_img: "sortable_img",
          post_order_ids: post_order_ids
        },
        success: function (data) {},
      });
    },
  });
  }); // Close setTimeout
}); //end of jquery ready

function deleteData(table, fld, id, url) {
      console.log("deleteData called with:", {table, fld, id, url});
      
      $.ajax({
        url: "php_action/ajax_deleteData.php",
        type: "post",
        data: {
          table: table,
          fld: fld,
          delete_id: id,
          url: url
        },
        dataType: "json",
        success: function (response) {
          console.log("Response received:", response);
          
                     if (response.sts === "success") {
             console.log("Showing success message");
             Swal.fire({
               title: "Deleted!",
               text: response.msg,
               icon: "success",
               confirmButtonText: "OK",
               timer: 3000,
               timerProgressBar: true
             }).then((result) => {
               setTimeout(() => {
                 window.location = url;
               }, 1000);
             });
           } else {
            console.log("Showing error message");
            Swal.fire({
              title: "Error!",
              text: response.msg || "Something went wrong.",
              icon: "error",
              confirmButtonText: "OK"
            });
          }
        },
        error: function (xhr, status, error) {
          console.log("Ajax error:", {xhr, status, error});
          Swal.fire({
            title: "Error!",
            text: "Server request failed. Try again.",
            icon: "error",
            confirmButtonText: "OK"
          });
        }
      });
    }
$("#add_nav_menus_fm").on("submit", function (e) {
  e.preventDefault();
  e.stopPropagation();
  var form = $("#add_nav_menus_fm");
  var nav_page = $("#nav_page").val();
  $.ajax({
    type: "POST",
    url: form.attr("action"),
    data: new FormData(this),
    contentType: false,
    cache: false,
    processData: false,
    dataType: "json",
    beforeSend: function () {
      $("#add_nav_menus_btn").prop("disabled", true);
      // $('#saveData1').text("Loading...");
    },
    success: function (responeID) {
      $("#add_nav_menus_btn").prop("disabled", false);
      $("#add_nav_menus_fm").each(function () {
        this.reset();
      });
      if (responeID.sts == "success") {
        sweeetalert("Menu has been Added", "success", 2000);
        $("#add_nav_table").load(location.href + " #add_nav_table");
      }
      if (responeID.sts == "info") {
        sweeetalert("Menu has been Updated", "info", 2000);
        $("#add_nav_table").load(location.href + " #add_nav_table");
      }
      if (nav_page == "#") {
        location.reload();
      }
    },
  }); //ajax call
}); //main

function sweeetalert(text, status, time) {
  Swal.fire({
    position: "center",
    icon: status,
    title: text,
    showConfirmButton: false,
    timer: time,
  });
}

function sameValue(id, id2) {
  $("" + id2).val(id);
}

function resetForm(id) {
  document.getElementById(id).reset();
}

function deleteAlert(id, table, row, reload_type) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "php_action/ajax_deleteData.php",
        type: "post",
        data: {
          delete_bymanually: id,
          table: table,
          row: row
        },
        dataType: "json",
        success: function (response) {
          //console.log(response);
          if (response.sts == "success") {
            if (reload_type != "pg") {
              $("#" + reload_type).load(
                location.href + " #" + reload_type + " > *"
              );
            } else {
              location.reload();
            }
          }

          Swal.fire("Deleted!", response.msg, response.sts);
        },
      }); //ajax
      console.log("Deleted");
    }
  });
}

function reload_page() {
  location.reload();
}

$(document).ready(function () {
  $("#myForm").submit(function (e) {
    e.preventDefault();

    const formData = $(this).serialize();

    $.ajax({
      url: $(this).attr("action"),
      type: $(this).attr("method"),
      data: formData,
      dataType: "json",
      success: function (response) {
        if (response.sts === "success") {
          Swal.fire("Success!", response.msg, "success").then(() => {
            $("#myForm")[0].reset(); // Corrected
            $("#new_warehouse_id").val(null).trigger("change"); // Corrected Select2 reset
            location.reload(); // Or call reload_page() if it's defined
          });
        } else {
          Swal.fire("Error!", response.msg || "Something went wrong.", "error");
        }
      },
      error: function () {
        Swal.fire("Error!", "Server request failed. Try again.", "error");
      },
    });
  });
});

function previewImage(url) {
  try {
    Swal.fire({
      imageUrl: url,
      imageAlt: 'Preview',
      showCloseButton: true,
      showConfirmButton: false,
      width: 'auto',
      customClass: {
        popup: 'p-0'
      }
    });
  } catch (e) {
    window.open(url, '_blank');
  }
}