// -------------------- Admin Side --------------------

jQuery(function () {
	// if code has been sended
	if (code) {
		// Get current exam
		$.ajax({
			url: `${baseURL}/admin/get_current_exam`,
			method: "POST",
			data: { code: code },
			dataType: "JSON",
			success: function (data) {
				// Change name of table title
				$(".table-title").text(`Questions Table : ${data.title}`);

				// Show questions table
				$("#questions-table").DataTable({
					serverSide: true,
					processing: true,
					order: [],
					ajax: {
						url: `${baseURL}/admin/fetch_all_questions`,
						type: "POST",
						data: { id: data.id },
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

				// Create Question
				$("#create-question").on("click", function () {
					$.ajax({
						url: `${baseURL}/admin/is_allowed_add_question`,
						method: "POST",
						data: { id: data.id },
						dataType: "JSON",
						success: function (allowed) {
							if (allowed) {
								// change modal title
								$(".modal-title").text("Create New Question");
								// change text at button submit
								$("#question-submit").text("Create Question");
								// set value action to 'create'
								$("#action").val("create");
								// reset form input
								$("#question-form")[0].reset();
								// display modal dialog
								$("#questionModal").modal("show");
								// change invalid feedback text
								$(".invalid-question").text("");
								$(".invalid-option-a").text("");
								$(".invalid-option-b").text("");
								$(".invalid-option-c").text("");
								$(".invalid-option-d").text("");
								$(".invalid-option-e").text("");
								$(".invalid-answer").text("");
								$(".invalid-type").text("");
								// set value exam-id
								$("#exam-id").val(data.id);
								// set value question-id
								$("#question-id").val(null);
							} else {
								Swal.fire({
									title: "Can't Add Questions",
									icon: "error",
									text:
										"The questions for this exam are full.",
									confirmButtonColor: "#52616B",
									confirmButtonText: "Ok, got it!",
									background: "#ffffff",
								});
							}
						},
						error: function (err) {
							console.log(err);
						},
					});
				});

				// Edit Question
				$(document).on("click", "#btn-question-edit", function () {
					if ($(this).data("editable")) {
						Swal.fire({
							title: "Unable To Edit",
							icon: "error",
							text:
								"The exam of this question has been completed or is on progress.",
							confirmButtonColor: "#52616B",
							confirmButtonText: "Ok, got it!",
							background: "#ffffff",
						});
					} else {
						const id = $(this).data("id");

						$.ajax({
							url: `${baseURL}/admin/fetch_single_question`,
							method: "POST",
							data: { id: id },
							dataType: "JSON",
							success: function (result) {
								// fill input with existing data
								$("#question-title").val(result.questionTitle);
								$("#option-a").val(result.optionA);
								$("#option-b").val(result.optionB);
								$("#option-c").val(result.optionC);
								$("#option-d").val(result.optionD);
								$("#option-e").val(result.optionE);
								$("#answer").val(result.answer);
								$("#type").val(result.type);
								// change modal title
								$(".modal-title").text("Edit Question");
								// change text at button submit
								$("#question-submit").text("Save Changes");
								// change value action to 'edit'
								$("#action").val("edit");
								// show modal
								$("#questionModal").modal("show");
								// change invalid feedback text
								$(".invalid-question").text("");
								$(".invalid-option-a").text("");
								$(".invalid-option-b").text("");
								$(".invalid-option-c").text("");
								$(".invalid-option-d").text("");
								$(".invalid-option-e").text("");
								$(".invalid-answer").text("");
								$(".invalid-type").text("");
								// set value input named exam-id
								$("#exam-id").val(null);
								// set value input named question-id
								$("#question-id").val(id);
							},
							error: function (err) {
								console.log(err);
							},
						});
					}
				});

				// When clicking submit button at question form, then
				$("#question-form").on("submit", function (e) {
					e.preventDefault();

					const submitQuestionText = $("#question-submit").text();

					$.ajax({
						url: `${baseURL}/admin/question_action`,
						method: "POST",
						data: $(this).serialize(),
						dataType: "JSON",
						beforeSend: function () {
							$("#question-submit").text("Wait...");
							$("#question-submit").attr("disabled", "disabled");
						},
						success: function (result) {
							$("#question-submit").text(submitQuestionText);
							$("#question-submit").attr("disabled", false);

							if (result.error == "yes") {
								// change invalid feedback text
								$(".invalid-type").text(result.invalidType);
								$(".invalid-answer").text(result.invalidAnswer);
								$(".invalid-question").text(
									result.invalidQuestion
								);
								$(".invalid-option-a").text(
									result.invalidOptionA
								);
								$(".invalid-option-b").text(
									result.invalidOptionB
								);
								$(".invalid-option-c").text(
									result.invalidOptionC
								);
								$(".invalid-option-d").text(
									result.invalidOptionD
								);
								$(".invalid-option-e").text(
									result.invalidOptionE
								);

								// change color border input to red if validation has error
								if (result.invalidQuestion == "") {
									$(".invalid-question")
										.prev("textarea")
										.removeClass("is-invalid");
								} else {
									$(".invalid-question")
										.prev("textarea")
										.addClass("is-invalid");
								}

								if (result.invalidOptionA == "") {
									$(".invalid-option-a")
										.prev("input")
										.removeClass("is-invalid");
								} else {
									$(".invalid-option-a")
										.prev("input")
										.addClass("is-invalid");
								}

								if (result.invalidOptionB == "") {
									$(".invalid-option-b")
										.prev("input")
										.removeClass("is-invalid");
								} else {
									$(".invalid-option-b")
										.prev("input")
										.addClass("is-invalid");
								}

								if (result.invalidOptionC == "") {
									$(".invalid-option-c")
										.prev("input")
										.removeClass("is-invalid");
								} else {
									$(".invalid-option-c")
										.prev("input")
										.addClass("is-invalid");
								}

								if (result.invalidOptionD == "") {
									$(".invalid-option-d")
										.prev("input")
										.removeClass("is-invalid");
								} else {
									$(".invalid-option-d")
										.prev("input")
										.addClass("is-invalid");
								}

								if (result.invalidOptionE == "") {
									$(".invalid-option-e")
										.prev("input")
										.removeClass("is-invalid");
								} else {
									$(".invalid-option-e")
										.prev("input")
										.addClass("is-invalid");
								}

								if (result.invalidAnswer == "") {
									$(".invalid-answer")
										.prev("select")
										.removeClass("is-invalid");
								} else {
									$(".invalid-answer")
										.prev("select")
										.addClass("is-invalid");
								}

								if (result.invalidType == "") {
									$(".invalid-type")
										.prev("select")
										.removeClass("is-invalid");
								} else {
									$(".invalid-type")
										.prev("select")
										.addClass("is-invalid");
								}
							} else {
								// hide modal
								$("#questionModal").modal("hide");

								// success alert message for create or edit question
								Swal.fire({
									title: "Success",
									icon: "success",
									text: result.message,
									confirmButtonColor: "#52616B",
									confirmButtonText: "Ok, got it!",
									background: "#ffffff",
								});

								// reload DataTable
								$("#questions-table").DataTable().ajax.reload();

								// reset invalid input border color
								$(".invalid-question")
									.prev("textarea")
									.removeClass("is-invalid");
								$(".invalid-option-a")
									.prev("input")
									.removeClass("is-invalid");
								$(".invalid-option-b")
									.prev("input")
									.removeClass("is-invalid");
								$(".invalid-option-c")
									.prev("input")
									.removeClass("is-invalid");
								$(".invalid-option-d")
									.prev("input")
									.removeClass("is-invalid");
								$(".invalid-option-e")
									.prev("input")
									.removeClass("is-invalid");
								$(".invalid-answer")
									.prev("select")
									.removeClass("is-invalid");
								$(".invalid-type")
									.prev("select")
									.removeClass("is-invalid");
							}
						},
						error: function (err) {
							console.log(err);
						},
					});
				});

				// When clicking cancel button at question form, then
				$("#question-cancel-submit").on("click", function () {
					// reset invalid input border color
					$(".invalid-question")
						.prev("textarea")
						.removeClass("is-invalid");
					$(".invalid-option-a")
						.prev("input")
						.removeClass("is-invalid");
					$(".invalid-option-b")
						.prev("input")
						.removeClass("is-invalid");
					$(".invalid-option-c")
						.prev("input")
						.removeClass("is-invalid");
					$(".invalid-option-d")
						.prev("input")
						.removeClass("is-invalid");
					$(".invalid-option-e")
						.prev("input")
						.removeClass("is-invalid");
					$(".invalid-answer")
						.prev("select")
						.removeClass("is-invalid");
					$(".invalid-type").prev("select").removeClass("is-invalid");
				});

				// Delete question
				$(document).on("click", "#btn-question-delete", function () {
					if ($(this).data("editable")) {
						Swal.fire({
							title: "Unable To Delete",
							icon: "error",
							text:
								"The exam of this question has been completed or is on progress.",
							confirmButtonColor: "#52616B",
							confirmButtonText: "Ok, got it!",
							background: "#ffffff",
						});
					} else {
						Swal.fire({
							title: "Delete This question?",
							text: "Deleted data cannot be restored.",
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
									url: `${baseURL}/admin/question_delete`,
									method: "POST",
									data: { id: id },
									success: function (result) {
										// success alert message for deleted data
										Swal.fire({
											title: "Deleted",
											icon: "success",
											text: result,
											confirmButtonColor: "#52616B",
											confirmButtonText: "Ok, got it!",
											background: "#ffffff",
										});
										// reload DataTable
										$("#questions-table")
											.DataTable()
											.ajax.reload();
									},
									error: function (err) {
										console.log(err);
									},
								});
							}
						});
					}
				});
			},
			error: function (err) {
				console.log(err);
			},
		});
	}
});
