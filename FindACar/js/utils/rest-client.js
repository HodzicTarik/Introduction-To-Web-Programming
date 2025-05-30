if (typeof RestClient === "undefined") {
  var RestClient = {
    get: function (url, callback, error_callback) {
      const token = localStorage.getItem("user_token");
      $.ajax({
        url: Constants.PROJECT_BASE_URL + url,
        type: "GET",
        beforeSend: function (xhr) {
          if (token) {
            xhr.setRequestHeader("Authorization", "Bearer " + token);
          }
        },
        success: callback,
        error: function (jqXHR) {
          if (error_callback) error_callback(jqXHR);
          else toastr.error(jqXHR.responseText || "GET error.");
        }
      });
    },

    post: function (url, data, callback, error_callback) {
      $.ajax({
        url: Constants.PROJECT_BASE_URL + url,
        type: "POST",
        data: JSON.stringify(data),
        contentType: "application/json",
        beforeSend: function (xhr) {
          xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem("user_token"));
        },
        success: function (response) {
          if (callback) callback(response);
        },
        error: function (jqXHR) {
          if (error_callback) error_callback(jqXHR);
          else toastr.error(jqXHR.responseText || "POST error.");
        }
      });
    },

    put: function (url, data, callback, error_callback) {
      $.ajax({
        url: Constants.PROJECT_BASE_URL + url,
        type: "PUT",
        data: JSON.stringify(data),
        contentType: "application/json",
        beforeSend: function (xhr) {
          xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem("user_token"));
        },
        success: function (response) {
          if (callback) callback(response);
        },
        error: function (jqXHR) {
          if (error_callback) error_callback(jqXHR);
          else toastr.error(jqXHR.responseText || "PUT error.");
        }
      });
    },

    patch: function (url, data, callback, error_callback) {
      $.ajax({
        url: Constants.PROJECT_BASE_URL + url,
        type: "PATCH",
        data: JSON.stringify(data),
        contentType: "application/json",
        beforeSend: function (xhr) {
          xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem("user_token"));
        },
        success: function (response) {
          if (callback) callback(response);
        },
        error: function (jqXHR) {
          if (error_callback) error_callback(jqXHR);
          else toastr.error(jqXHR.responseText || "PATCH error.");
        }
      });
    },

    delete: function (url, data, callback, error_callback) {
      $.ajax({
        url: Constants.PROJECT_BASE_URL + url,
        type: "DELETE",
        data: data ? JSON.stringify(data) : null,
        contentType: "application/json",
        beforeSend: function (xhr) {
          xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem("user_token"));
        },
        success: function (response) {
          if (callback) callback(response);
        },
        error: function (jqXHR) {
          if (error_callback) error_callback(jqXHR);
          else toastr.error(jqXHR.responseText || "DELETE error.");
        }
      });
    }
  };
}
