/**
 * collex.js
 *
 * Author: Center for Digital Humanities - University of South Carolina.
 * Version: Alpha.
 * Copyright: 2015. All Rights Reserved.
 */

/**
 * Executed when the DOM is ready.
 */
$(document).ready(function() {
  // Internet Explorer v9 and below detection.
  if (platform.name == "IE" && parseInt(platform.version, 10) < 10) {
    $("#alerter").show();
  } else {
    $("#alerter").remove();
  }

  // Initialize all tooltips.
  $("[data-toggle='tooltip']").tooltip();

  // Initialize all DataTables.
  $(".dt").dataTable();
});

/**
 * Executed when the window is ready.
 * Note: This will always execute after the DOM is ready.
 */
$(window).ready(function () {
  if ($("#editSuccess").length) {
    editSuccess();
  }
});

/**
 * For all required <input> and <textarea>, toggle the has-error class if the inside value is empty.
 */
$("form").on("input", "input, textarea", function () {
  if ($(this).attr("required") === undefined) { return; }

  if ($(this).val() === "") {
    $(this).parent().addClass("has-error");
  } else if ($(this).val() !== "") {
    $(this).parent().removeClass("has-error");
  }
});

/**
 * Add another role to the role section within the RDF creation or edit form.
 *
 * @param {HTML DOM Event} e: The event happening.
 */
$("#addRoleButton").click(function (e) {
  var section = $(this).parentsUntil("section").parent();
  var group   = section.find("select[name='role[]']").last().parent().parent().clone();
  var newID   = increaseID(group, "select");

  group.find("select").prop("id", newID).find("> option:selected").removeAttr("selected").parent().find("> option:first-child").prop("selected", "true");
  group.find("label").attr("for", newID);

  $(group).insertBefore($(this).parent().parent());
  group.show();

  group = section.find("input[name='role_value[]']").last().parent().parent().clone();
  newID = increaseID(group, "input");

  group.find("input").prop("id", newID).val("");
  group.find("label").attr("for", newID);

  $(group).insertBefore($(this).parent().parent());
  group.show();

  section.find(".close.hide").removeClass("hide");

  e.target.blur();
});

/**
 * Add another genre to the genre section within the RDF creation or edit form.
 *
 * @param {HTML DOM Event} e: The event happening.
 */
$("#addGenreButton").click(function (e) {
  var section = $(this).parentsUntil("section").parent();
  var group   = section.find("select[name='genre[]']").last().parent().parent().clone();
  var newID   = increaseID(group, "select");

  group.find("select").prop("id", newID).find("> option:selected").removeAttr("selected").parent().find("> option:first-child").prop("selected", "true");
  group.find("label").attr("for", newID);

  $(group).insertBefore($(this).parent().parent());
  group.show();

  section.find(".close.hide").removeClass("hide");

  e.target.blur();
});

/**
 * Add another language within the RDF creation or edit form.
 *
 * @param {HTML DOM Event} e: The event happening.
 */
$("#addLanguageButton").click(function (e) {
  var section = $(this).parentsUntil("section").parent();
  var group   = section.find("input[name='language[]']").last().parent().parent().clone();
  var newID   = increaseID(group, "input");

  group.find("input").prop("id", newID).val("");
  group.find("label").attr("for", newID);

  $(group).insertBefore($(this).parent().parent());
  group.show();

  section.find(".close.hide").removeClass("hide");

  e.target.blur();
});

/**
 * Add another alternative title to the alternative title section within the RDF creation or edit form.
 *
 * @param {HTML DOM Event} e: The event happening.
 */
$("#addAltTitleButton").click(function (e) {
  var section = $(this).parentsUntil("section").parent();
  var group   = section.find("input[name='alternative_title[]']").last().parent().parent().clone();
  var newID   = increaseID(group, "input");

  group.find("input").prop("id", newID).val("");
  group.find("label").attr("for", newID);

  $(group).insertBefore($(this).parent().parent());
  group.show();

  section.find(".close.hide").removeClass("hide");

  e.target.blur();
});

/**
 * Add another hasPart to the hasPart section within the RDF creation or edit form.
 *
 * @param {HTML DOM Event} e: The event happening.
 */
$("#addHasPartButton").click(function (e) {
  var section = $(this).parentsUntil("section").parent();
  var group   = section.find("input[name='has_part[]']").last().parent().parent().clone();
  var newID   = increaseID(group, "input");

  group.find("input").prop("id", newID).val("");
  group.find("label").attr("for", newID);

  $(group).insertBefore($(this).parent().parent());
  group.show();

  section.find(".close.hide").removeClass("hide");

  e.target.blur();
});

/**
 * Add another isPartOf to the isPartOf section within the RDF creation or edit form.
 *
 * @param {HTML DOM Event} e: The event happening.
 */
$("#addIsPartOfButton").click(function (e) {
  var section = $(this).parentsUntil("section").parent();
  var group   = section.find("input[name='is_part_of[]']").last().parent().parent().clone();
  var newID   = increaseID(group, "input");

  group.find("input").prop("id", newID).val("");
  group.find("label").attr("for", newID);

  $(group).insertBefore($(this).parent().parent());
  group.show();

  section.find(".close.hide").removeClass("hide");

  e.target.blur();
});

/**
 * Remove a form group within the RDF creation or edit form.
 *
 * @param {HTML DOM Event} e: The event happening.
 */
$("section").on("click", ".control-label > .close", function (e) {
  var group   = $(this).parentsUntil("div.form-group").parent();
  var section = $(this).parentsUntil("section").parent();

  // fadeOut for visuals, then remove from the DOM.
  if (section.prev().get(0).innerText == "Role" && $(this).parent().text().substring(1) == "Role") {
    group.next().fadeOut(function () {
      $(this).remove();
    });
  } else if (section.prev().get(0).innerText == "Role" && $(this).parent().text().substring(1) == "Value") {
    group.prev().fadeOut(function () {
      $(this).remove();
    });
  }

  group.fadeOut(function () {
    $(this).remove();
  });

  // Determine if users need visual access to this functionality again.
  if ((section.prev().get(0).innerText == "Role" && section.find(".close").length == 4) || (section.prev().get(0).innerText != "Role" && section.find(".close").length == 2)) {
    section.find(".close").addClass("hide");
  }

  e.target.blur();
});

/**
 * Visually duplicate a user selection inside add modal on edit.
 *
 * @param {HTML DOM Event} e: The event happening.
 */
$(".list-part > button").click(function (e) {
  var section  = $(this).parentsUntil("section").parent();
  var modal    = $(this).parentsUntil("[role='dialog']").parent().prop("id");
  var oldGroup = modal == "isPartOfModal" ? section.find("input[name='is_part_of[]']").last().parent().parent() : section.find("input[name='has_part[]']").last().parent().parent();
  var group    = oldGroup.clone();
  var newID    = increaseID(group, "input");

  group.find("input").prop("id", newID).val($(this).val());
  group.find("label").attr("for", newID);
  group.find("a").attr("href", "view?id=" + $(this).val()).text($(this).attr("title"));

  $(group).insertAfter(oldGroup);
  group.show();

  section.find(".close.hide").removeClass("hide");

  $("#" + modal).modal("hide");

  e.target.blur();
});

/**
 * Visually slide the user down to add a new comment.
 *
 * @param {HTML DOM Event} e: The event happening.
 */
$("#newCommentButton").click(function (e) {
  $("html, body").animate({
    scrollTop: $("#new").position().top
  }, 1200);

  e.target.blur();
});

/**
 * Renders all comments for super users.
 */
$(".viewer").click(function () {
  $(".viewer.viewer-active").removeClass("viewer-active");
  $(this).addClass("viewer-active");

  $.ajax({
    url: "comments",
    type: "GET",
    data: "comments=" + $(this).data("value"),
    success: function (result) {
      if ($.trim(result) === "") {
        $("#results").html("<h3><em>No comments have been made, yet...</em></h3>");
      } else {
        $("#results").html(result).find("table.dt").dataTable();
        $("#results").find(".breakdown").each(function () {
          if (!$(this).parentsUntil(".comment").parent().next().is(".comment-reply")) {
            $(this).remove();
          }
        });
      }
    },
    error: function (result) {
      console.error("Error connecting to the server. Message: " + result.responseText);
    }
  });
});

/**
 * Displays a textbox for a user wanting to reply.
 */
$("#results").on("click", ".reply", function () {
  var group = $(this).parentsUntil("div").parent();
  var reply = $("<div class='col-xs-9' style='padding-left: 0; padding-right: 0; margin-bottom: 1%;'><textarea placeholder='Reply to the comment...'></textarea><a class='btn btn-default pull-right' style='margin-top: 1%;'>Submit</a></div>");
  reply.hide().css("margin-left", group.css("margin-left")).find("textarea").addClass("form-control").parent().insertAfter(group).slideDown();
});

/**
 * Submits a reply comment.
 */
$("#results").on("click", "a.btn-default", function (e) {
  var id    = $(this).parent().prev().find("> h4").data("id");
  var table = $(this).parent().prev().find("> h4").data("tablename");
  var value = $.trim($(this).prev().val());

  $.ajax({
    url: "comments",
    type: "POST",
    data: "postComment=" + value + "&commentID=" + id + "&tablename=" + table,
    success: function (result) {
      $("#results").html(result);
    },
    error: function (result) {
      console.error("There was an error connecting to the server: " + result.responseText);
    }
  });

  e.target.blur();
});

/**
 * Deletes a reply comment.
 *
 * {HTML DOM Event} e: The event happening.
 */
$("#results").on("click", ".delete", function (e) {
  var id    = $(this).parent().parent().find("> h4").data("id");
  var table = $(this).parent().parent().find("> h4").data("tablename");

  $.ajax({
    url: "comments",
    type: "POST",
    data: "commentID=" + id + "&tablename=" + table,
    success: function (result) {
      $("#results").html(result);
    },
    error: function (result) {
      console.error("There was an error connecting to the server: " + result.responseText);
    }
  });

  e.target.blur();
});

/**
 * Collapses and expands a comment's replies.
 */
$("#results").on("click", ".breakdown", function () {
  if ($(this).text() == "Collapse") {
    $(this).text("Expand").parentsUntil(".comment").parent().nextUntil(".comment").slideUp();
  } else if ($(this).text() == "Expand") {
    $(this).text("Collapse").parentsUntil(".comment").parent().nextUntil(".comment").slideDown();
  }
});

/**
 * Sends off a verification email.
 *
 * @param {HTML DOM Event} e: The event happening.
 */
$("#verification").click(function (e) {
  if ($(this).next().is("span.label")) { return; }
  var button = $(this);
  $.ajax({
    url: "account",
    type: "POST",
    data: {
      resend: $(this).parent().find("> span").text()
    },
    dataType: "json",
    beforeSend: function () {
      $("<span class='label label-primary' style='position: relative; top: 3px; left: 5px;'>Sending...</span>").insertAfter(button);
    },
    success: function (result) {
      if (result.type === 0) {
        button.next().toggleClass("label-primary label-warning").text("Failed");
      } else if (result.type == 1) {
        button.next().toggleClass("label-primary label-success").text("Sent");
      }
    },
    error: function (result) {
      button.next().toggleClass("label-primary label-danger").text("Error");
      console.error("Error. Result: " + result.responseText);
    }
  });
  e.target.blur();
});

/**
 * Verify that the password inputs are the same before server-side verification.
 */
$("form#passwordUpdate").on("input", "input", function () {
  var name  = $(this).prop("name");
  var value = $.trim($(this).val());
  var pass1 = $.trim($("#passwordUpdate input#password1").val());
  var pass2 = $.trim($("#passwordUpdate input#password2").val());

  // Compare the password inputs with each other.
  if ((name == "password1" && pass2 !== "" && value !== pass2) || (name == "password2" && pass1 !== "" && value !== pass1)) {
    $("input#password1, input#password2").parent().addClass("has-error");
  } else {
    $("input#password1, input#password2").parent().removeClass("has-error");
  }
});

/**
 * Activates and manipulates the contact modal.
 *
 * @param {HTML DOM Event} e: The event happening.
 */
$("li.dropdown").on("click", "li > a[data-target]", function (e) {
  if ($(this).data("target") === 0) {
    $("footer.modal .modal-title").text("Contact Colin Wilder");
  } else {
    $("footer.modal .modal-title").text("Contact Abigail Firey");
  }

  $("footer.modal").modal("show");

  e.target.blur();
  e.preventDefault();
});

/**
 * Send off contact information.
 *
 * @param {HTML DOM Event} e: The event happening.
 */
$("body").on("submit", "footer.modal form", function (e) {
  var name     = $.trim($(this).find("input[name='name']").val());
  var email    = $.trim($(this).find("input[name='email']").val());
  var message  = $.trim($(this).find("textarea[name='message']").val());
  var captcha  = $.trim($("[name='g-recaptcha-response']").val());
  var alerter  = $(this).find(".alert");
  var receiver = $(this).find(".modal-title").text().split(" ")[1];

  alerter.removeClass("alert-info alert-warning alert-danger alert-success").empty().fadeOut();

  if (name === "") {
    $(this).find("input[name='name']").parent().addClass("has-error");
    alerter.addClass("alert-warning").html("<h4>Warning</h4><p>Please enter in a name.</p>").fadeIn();
    return false;
  }

  if (email === "" || !(/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i).test(email)) {
    $(this).find("input[name='email']").parent().addClass("has-error");
    alerter.addClass("alert-warning").html("<h4>Warning</h4><p>Please enter in a valid email.</p>").fadeIn();
    return false;
  }

  if (message === "") {
    $(this).find("textarea[name='message']").parent().addClass("has-error");
    alerter.addClass("alert-warning").html("<h4>Warning</h4><p>Please enter in a message.</p>").fadeIn();
    return false;
  }

  $.ajax({
    url: window.location.href,
    type: "POST",
    data: {
      name: name,
      email: email,
      message: message,
      captcha: captcha,
      receiver: receiver
    },
    dataType: "json",
    beforeSend: function () {
      alerter.addClass("alert-info").html("<p>Sending off your email now...</p>").fadeIn();
    },
    success: function (result) {
      alerter.removeClass("alert-info").addClass("alert-" + result.type).html(result.text);

      if (result.type == "success") {
        $("footer.modal form input, footer.modal form textarea").val("");
      }
    },
    error: function (result) {
      alerter.removeClass("alert-info").addClass("alert-danger").html("<h4>Error</h4><p>There was an error connecting to the server.</p><p>" + result.responseText + "</p>");
    }
  });

  e.preventDefault();
  return false;
});

/**
 * Increase a ID on a <input> or <select> for better user experience when
 * a user clicks the corresponding <label>.
 *
 * @param {HTML Element} group: The .form-group of the <input>/<select> and <label>
 * @param {String} search: The form field to be looked for (can be input or select).
 * @return {String}
 */
function increaseID(group, search) {
  var id = group.find(search).prop("id");

  if ((/^\d+$/).test(id.substring(id.length - id.replace(/\D/g, ""), id.length))) {
    var number = parseInt(id.substring(id.length - id.replace(/\D/g, "").length, id.length), 10);

    return id.substring(0, id.length - number.toString().length) + (number + 1);
  } else {
    return id + "1";
  }
}

/**
 * Display to the user that they were successful on editing an object.
 */
function editSuccess() {
  $("#editSuccess").fadeIn();

  setTimeout(function () {
    $("#editSuccess").fadeOut();
  }, 1500);
}
