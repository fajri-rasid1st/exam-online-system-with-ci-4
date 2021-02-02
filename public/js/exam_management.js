// set DateTimePicker
const date = new Date();

date.setDate(date.getDate());

jQuery.datetimepicker.setLocale("id");

$("#schedule").datetimepicker({
	startDate: date,
	format: "Y-m-d H:i:s",
	lang: "id",
	step: 15,
	autoClose: true,
	onShow: function (_) {
		this.setOptions({
			minDate: 0,
		});
	},
});

jQuery(function () {
	// Show Exams Table
	$("#exams-table").DataTable({
		serverSide: true,
		processing: true,
		order: [],
		ajax: {
			url: `${baseURL}/admin/fetch_all_exams`,
			type: "POST",
			error: function (err) {
				console.log(err);
			},
		},
		columnDefs: [
			{
				targets: [4],
				orderable: false,
			},
			{
				targets: [5],
				orderable: false,
			},
		],
	});

	// Create Examination
	$("#create-exam").on("click", function () {
		// change modal title
		$(".modal-title").text("Create New Exam");
		// change text at button submit
		$("#exam-submit").text("Create Exam");
		// change value action to 'create'
		$("#action").val("create");
		// reset form input
		$("#exam-form")[0].reset();
		// display modal dialog
		$("#examModal").modal("show");
		// change invalid feedback text
		$(".invalid-title").text("");
		$(".invalid-schedule").text("");
		// reset slider value
		$(".duration-value").text("5");
		$(".question-value").text("5");
		$(".right-answer-value").text("0");
		$(".wrong-answer-value").text("-10");
		$(".empty-answer-value").text("-10");
		// set value input named exam-id
		$("#exam-id").val(null);
	});

	// Edit Examination
	$(document).on("click", "#btn-exam-edit", function () {
		if ($(this).data("editable")) {
			Swal.fire({
				title: "Unable To Edit",
				icon: "error",
				text: "This exam has been completed or is on progress.",
				confirmButtonColor: "#52616B",
				confirmButtonText: "Ok, got it!",
				background: "#ffffff",
			});
		} else {
			const id = $(this).data("id");

			$.ajax({
				url: `${baseURL}/admin/fetch_single_exam`,
				method: "POST",
				data: { id: id },
				dataType: "JSON",
				success: function (data) {
					// fill input with existing data
					$("#title").val(data.title);
					$("#schedule").val(data.implement_date);
					// change slider position according to value
					$("#duration").val(data.duration);
					$("#question").val(data.total_question);
					$("#right-answer").val(data.score_per_right_answer);
					$("#wrong-answer").val(data.score_per_wrong_answer);
					$("#empty-answer").val(data.score_per_empty_answer);
					// change slider value
					$(".duration-value").text($("#duration").val());
					$(".question-value").text($("#question").val());
					$(".right-answer-value").text($("#right-answer").val());
					$(".wrong-answer-value").text($("#wrong-answer").val());
					$(".empty-answer-value").text($("#empty-answer").val());
					// change modal title
					$(".modal-title").text("Edit Exam");
					// change text at button submit
					$("#exam-submit").text("Save Changes");
					// change value action to 'edit'
					$("#action").val("edit");
					// show modal
					$("#examModal").modal("show");
					// change invalid feedback text
					$(".invalid-title").text("");
					$(".invalid-schedule").text("");
					// set value input named exam-id
					$("#exam-id").val(id);
				},
				error: function (err) {
					console.log(err);
				},
			});
		}
	});

	// When clicking submit button at exam form, then
	$("#exam-form").on("submit", function (e) {
		e.preventDefault();

		const submitExamText = $("#exam-submit").text();

		$.ajax({
			url: `${baseURL}/admin/exam_action`,
			method: "POST",
			data: $(this).serialize(),
			dataType: "JSON",
			beforeSend: function () {
				$("#exam-submit").text("Wait...");
				$("#exam-submit").attr("disabled", "disabled");
			},
			success: function (data) {
				$("#exam-submit").text(submitExamText);
				$("#exam-submit").attr("disabled", false);

				if (data.error == "yes") {
					// change invalid feedback text
					$(".invalid-title").text(data.invalidTitle);
					$(".invalid-schedule").text(data.invalidSchedule);

					// change color border input to red if validation has error
					if (data.invalidTitle == "") {
						$(".invalid-title")
							.prev("input")
							.removeClass("is-invalid");
					} else {
						$(".invalid-title")
							.prev("input")
							.addClass("is-invalid");
					}

					if (data.invalidSchedule == "") {
						$(".invalid-schedule")
							.prev("input")
							.removeClass("is-invalid");
					} else {
						$(".invalid-schedule")
							.prev("input")
							.addClass("is-invalid");
					}
				} else {
					// hide modal
					$("#examModal").modal("hide");

					// success alert message for create or edit exam
					Swal.fire({
						title: "Success",
						icon: "success",
						text: data.message,
						confirmButtonColor: "#52616B",
						confirmButtonText: "Ok, got it!",
						background: "#ffffff",
					});

					// reload DataTable
					$("#exams-table").DataTable().ajax.reload();

					// reset invalid input border color
					$(".invalid-title").prev("input").removeClass("is-invalid");
					$(".invalid-schedule")
						.prev("input")
						.removeClass("is-invalid");
				}
			},
			error: function (err) {
				console.log(err);
			},
		});
	});

	// When clicking cancel button at exam form, then
	$("#exam-cancel-submit").on("click", function () {
		// reset invalid input border color
		$(".invalid-title").prev("input").removeClass("is-invalid");
		$(".invalid-schedule").prev("input").removeClass("is-invalid");
	});

	// Delete Examination
	$(document).on("click", "#btn-exam-delete", function () {
		if ($(this).data("editable")) {
			Swal.fire({
				title: "Unable To Delete",
				icon: "error",
				text: "This exam has been completed or is on progress.",
				confirmButtonColor: "#52616B",
				confirmButtonText: "Ok, got it!",
				background: "#ffffff",
			});
		} else {
			Swal.fire({
				title: "Delete This Exam?",
				html: `
					<span class="text-danger">
						There may be question(s) that have been made in this exam.
						If this exam is deleted, the question(s) is also deleted.
					</span>
				`,
				icon: "question",
				showCancelButton: true,
				confirmButtonColor: "#5A5C69",
				cancelButtonColor: "#858796",
				confirmButtonText: "Confirm",
				cancelButtonText: "Cancel",
			}).then((result) => {
				if (result.isConfirmed) {
					const id = $(this).data("id");

					$.ajax({
						url: `${baseURL}/admin/exam_delete`,
						method: "POST",
						data: { id: id },
						success: function (data) {
							// success alert message for deleted data
							Swal.fire({
								title: "Deleted",
								icon: "success",
								text: data,
								confirmButtonColor: "#52616B",
								confirmButtonText: "Ok, got it!",
								background: "#ffffff",
							});
							// reload DataTable
							$("#exams-table").DataTable().ajax.reload();
						},
						error: function (err) {
							console.log(err);
						},
					});
				}
			});
		}
	});
});
