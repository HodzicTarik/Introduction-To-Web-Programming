if (typeof Utils === "undefined") {
  var Utils = {
    datatable: function (table_id, columns, data, pageLength = 15) {
      if ($.fn.dataTable.isDataTable("#" + table_id)) {
        $("#" + table_id).DataTable().destroy();
      }
      $("#" + table_id).DataTable({
        data: data,
        columns: columns,
        pageLength: pageLength,
        lengthMenu: [2, 5, 10, 15, 25, 50, 100, "All"],
      });
    },

    parseJwt: function (token) {
      if (!token) return null;
      try {
        const payload = token.split(".")[1];
        return JSON.parse(atob(payload));
      } catch (e) {
        console.error("Invalid token:", e);
        return null;
      }
    },
  };
}
