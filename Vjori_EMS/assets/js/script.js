function checkPasswordMinimumLength() {
    if (document.getElementById('name').value === "admin") {
        document.getElementById('password').setAttribute("minlength", 4);
    }
}


function changeAdminPageToNewUser() {
    window.location.href = "admin-newUser.php";
}
/*Using AJAX to give data to server from client for different purpose*/
function register(id) {
    $.ajax({
        method: "POST",
        url: "myevents.php",
        data: "event_id=" + id,
        success: function (data) {
            window.location.href = "myevents.php";
        }

    });
}

function unregister(attendee_id, event_id) {
    $.ajax({
        method: "POST",
        url: "redirection.php",
        data: "delete_registered_attendee_id=" + attendee_id + "&delete_registered_attendee_event=" + event_id,
        success: function (data) {
            window.location.href = "myevents.php";
        }

    });
}


function unregisterSession(attendee_id, session_id) {
    $.ajax({
        method: "POST",
        url: "redirection.php",
        data: "delete_registered_session_attendee_id=" + attendee_id + "&delete_registered_session_attendee_event=" + session_id,
        success: function (data) {
            window.location.href = "myevents.php";
        }

    });
}

function updateSession(session_id, event_id) {
    $.ajax({
        method: "POST",
        url: "redirection.php",
        data: "session_id_for_update=" + session_id + "&session_event_id_for_update" + event_id,
        success: function (data) {
            window.location.href = "editSession.php";
        }
    });
}

function editEvent(id) {
    $.ajax({
        method: "POST",
        url: "redirection.php",
        data: "event_id_for_update=" + id,
        success: function (data) {
            window.location.href = "editEvent.php";
        }
    });
}

function viewEventPeople(id) {
    $.ajax({
        method: "POST",
        url: "redirection.php",
        data: "show_registered_people_per_event=" + id,
        success: function (data) {
            window.location.href = "viewPeople.php";
        }
    });
}

function viewSessionPeople(id) {
    $.ajax({
        method: "POST",
        url: "redirection.php",
        data: "show_registered_people_per_session=" + id,
        success: function (data) {
            window.location.href = "viewSessionPeople.php";
        }
    });
}

function deleteEvent(id) {
    $.ajax({
        method: "POST",
        url: "redirection.php",
        data: "delete_event=" + id,
        success: function (data) {
            window.location.href = "eventmanager.php";
        }
    });
}

function deleteSession(id) {
    $.ajax({
        method: "POST",
        url: "redirection.php",
        data: "delete_session=" + id,
        success: function (data) {
            window.location.href = "eventmanager.php";
        }
    });
}

function editUser(id) {
    $.ajax({
        method: "POST",
        url: "redirection.php",
        data: "attendee_id_for_update=" + id,
        success: function (data) {
            window.location.href = "admin-newUser.php";
        }
    });
}

function deleteUser(id) {
    $.ajax({
        method: "POST",
        url: "redirection.php",
        data: "delete_user=" + id,
        success: function (data) {
            window.location.href = "admin-registeredusers.php";
        }
    });
}

function editVenue(id) {
    $.ajax({
        method: "POST",
        url: "redirection.php",
        data: "venue_id_for_update=" + id,
        success: function (data) {
            window.location.href = "admin-newVenue.php";
        }
    });
}

function deleteVenue(id) {
    $.ajax({
        method: "POST",
        url: "redirection.php",
        data: "delete_venue=" + id,
        success: function (data) {
            window.location.href = "admin-venues.php";
        }
    });
}

//used for admin because it is 4 characters, and users are not allowed to login with less than 8, but admin is an exception
document.getElementById('login').onclick = checkPasswordMinimumLength;
