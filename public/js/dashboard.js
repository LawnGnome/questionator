$(function () {
  var createQuestionRow = function (id) {
    var row = $("<tr question='" + id + "' />");

    row.attr("question", id);
    row.append($("<td class='name' />"));
    row.append($("<td class='question' />"));

    return row;
  };

  var updateQuestion = function (row, data) {
    $(".name", row).text(data.name);
    $(".question", row).text(data.question);
  };

  var update = function () {
    $.getJSON("/questions", function (data) {
      var table = $("#questions");

      $.each(data, function () {
        var row = $("[question='" + this.id + "']", table)[0];

        if (!row) {
          row = createQuestionRow(this.id).appendTo(table);
        }

        updateQuestion(row, this);
      });
    });
  };

  update();
  window.setInterval(update, 1000);
});
