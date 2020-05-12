function generate() {
  var tasks = [];
  for (var i = 0; i < 20; i++) {
    var startTime = -1;
    var duration = 0.5;
    for (var j = 0; j < 10; j++) {
      if (Math.random() * 10 > 5) {
        startTime += 0.5 + duration;
      } else {
        startTime += 1 + duration;
      }

      if (startTime > 23) {
        break;
      }

      duration = Math.ceil(Math.random() * 2) + (Math.random() * 10 > 5 ? 0 : 0.5);

      duration -= startTime + duration > 24 ? (startTime + duration) - 24 : 0;

      var task = {
        startTime: startTime,
        duration: duration,
        column: i,
        id: 'test',
        title: ''
      };

      tasks.push(task);
    }
  }

  console.log("tasks count: " + tasks.length);

  $("#scheduler-container").skeduler({
    headers: ["Specialist 1", "Specialist 2", "Specialist 3"],
    tasks: tasks,
    cardTemplate: '<div>${id}</div><div>${title}</div>',
    onClick: function (e, t) { console.log(e, t); }
  });
}
