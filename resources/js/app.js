import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

// Project dates
const projectStart = document.querySelector("#project_start_date");
const projectEnd = document.querySelector("#project_end_date");

if (projectStart && projectEnd) {
    const projectEndPicker = flatpickr("#project_end_date", {
        dateFormat: "Y-m-d",
        minDate: projectStart.value || null
    });

    flatpickr("#project_start_date", {
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            projectEndPicker.set("minDate", selectedDates[0]);

            if (
                projectEndPicker.selectedDates.length &&
                projectEndPicker.selectedDates[0] < selectedDates[0]
            ) {
                projectEndPicker.clear();
            }
        }
    });
}

// const startInput = document.querySelector("#start_date");
// const endInput = document.querySelector("#end_date");

// if (startInput && endInput) {

//     const endPicker = flatpickr("#end_date", {
//         dateFormat: "Y-m-d",
//         minDate: startInput.value || null
//     });

//     flatpickr("#start_date", {
//         dateFormat: "Y-m-d",

//         onChange: function(selectedDates) {
//             endPicker.set("minDate", selectedDates[0]);

//             if (
//                 endPicker.selectedDates.length &&
//                 endPicker.selectedDates[0] < selectedDates[0]
//             ) {
//                 endPicker.clear();
//             }
//         }
//     });
// }

// Epic dates
const epicStart = document.querySelector("#start_date");
const epicEnd = document.querySelector("#end_date");

if (epicStart && epicEnd) {
    const endPicker = flatpickr("#end_date", {
        dateFormat: "Y-m-d",
        minDate: epicStart.value || null
    });

    flatpickr("#start_date", {
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            endPicker.set("minDate", selectedDates[0]);

            if (
                endPicker.selectedDates.length &&
                endPicker.selectedDates[0] < selectedDates[0]
            ) {
                endPicker.clear();
            }
        }
    });
}

// Sprint dates
const sprintStart = document.querySelector("#sprint_start_date");
const sprintEnd = document.querySelector("#sprint_end_date");

if (sprintStart && sprintEnd) {
    const sprintEndPicker = flatpickr("#sprint_end_date", {
        dateFormat: "Y-m-d",
        minDate: sprintStart.value || null
    });

    flatpickr("#sprint_start_date", {
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            sprintEndPicker.set("minDate", selectedDates[0]);

            if (
                sprintEndPicker.selectedDates.length &&
                sprintEndPicker.selectedDates[0] < selectedDates[0]
            ) {
                sprintEndPicker.clear();
            }
        }
    });
}


//task
const taskDueDate = document.querySelector("#task_due_date");

if (taskDueDate) {
    flatpickr(taskDueDate, {
        dateFormat: "Y-m-d",
        allowInput: false,
        clickOpens: true
    });
}


//subtasks
flatpickr("#modalDueDate", {
    dateFormat: "Y-m-d",
    maxDate: document.getElementById("task_due_date").value
});
