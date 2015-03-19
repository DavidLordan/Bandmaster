var showNewTaskBox = function () {
    $("#newTaskBox").removeClass("hidden");
    $("#newTaskButton").addClass("hidden");

};

var closeNewTaskBox = function () {
    $("#newTaskBox").addClass("hidden");
     $("#newTaskButton").removeClass("hidden");
};

var addTask = function () {
    $("#newTaskBox").addClass("hidden");
     $("#newTaskButton").removeClass("hidden");
    
    var str = $("#taskInput").val();
    $('#taskInput').val('');
     $.ajax({
            type: 'POST',
            url: 'functions.php',
            data: {
                func: "addTask",
                newTask: str
            }
        });
};



       

    