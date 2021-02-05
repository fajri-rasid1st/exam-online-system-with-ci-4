jQuery(function () {
	// Class name of input at question form
	const inputClassName = [
		".invalid-question",
		".invalid-option-a",
		".invalid-option-b",
		".invalid-option-c",
		".invalid-option-d",
		".invalid-option-e",
		".invalid-answer",
		".invalid-type",
	];
	// Id name of input at question form
	const inputIdName = [
		"#question-title",
		"#answer",
		"#type",
		"#option-a",
		"#option-b",
		"#option-c",
		"#option-d",
		"#option-e",
	];
	// If code has been sended
	if (code && page == "admin_exam_view") {
		// Get current exam
		$.ajax({
			url: `${baseURL}/admin/get_current_exam`,
			method: "POST",
			data: { code: code },
			dataType: "JSON",
			success: function (exam) {
				// Change name of table title
				$(".table-title").text(`Questions Table : ${exam.title}`);

				// Show questions table
				$("#questions-table").DataTable({
					serverSide: true,
					processing: true,
					order: [],
					ajax: {
						url: `${baseURL}/admin/fetch_all_questions`,
						type: "POST",
						data: { id: exam.id },
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

				// Create question
				$("#create-question").on("click", function () {
					if ($(this).data("disable")) {
						Swal.fire({
							title: "Can't Add Questions",
							icon: "error",
							text: "The exam is already locked.",
							confirmButtonColor: "#52616B",
							confirmButtonText: "Ok, got it!",
							background: "#ffffff",
							allowOutsideClick: false,
							allowEscapeKey: false,
						});
					} else {
						$.ajax({
							url: `${baseURL}/admin/is_allowed_add_question`,
							method: "POST",
							data: { id: exam.id },
							dataType: "JSON",
							success: function (allowed) {
								if (allowed) {
									// change modal title
									$(".modal-title").text(
										"Create New Question"
									);
									// change text at button submit
									$("#question-submit").text(
										"Create Question"
									);
									// set value action to 'create'
									$("#action").val("create");
									// reset form input
									$("#question-form")[0].reset();
									// display modal dialog
									$("#questionModal").modal("show");
									// set value exam-id
									$("#exam-id").val(exam.id);
									// set value question-id
									$("#question-id").val(null);
									// change invalid feedback text
									inputClassName.forEach((element) => {
										$(element).text("");
									});
								} else {
									Swal.fire({
										title: "Can't Add Questions",
										icon: "error",
										text: `
											The questions for this exam are full
											or this exam has been completed.
										`,
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
					}
				});

				// Edit question
				$(document).on("click", "#btn-question-edit", function () {
					if ($(this).data("editable")) {
						Swal.fire({
							title: "Unable To Edit",
							icon: "error",
							text: `
								The exam of this question has been completed or is on progress.
							`,
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
								let i = 0;
								// fill input with existing data
								for (const key in result) {
									$(inputIdName[i++]).val(result[key]);
								}
								// change modal title
								$(".modal-title").text("Edit Question");
								// change text at button submit
								$("#question-submit").text("Save Changes");
								// change value action to 'edit'
								$("#action").val("edit");
								// show modal
								$("#questionModal").modal("show");
								// set value input named exam-id
								$("#exam-id").val(null);
								// set value input named question-id
								$("#question-id").val(id);
								// change invalid feedback text
								inputClassName.forEach((element) => {
									$(element).text("");
								});
							},
							error: function (err) {
								console.log(err);
							},
						});
					}
				});

				// When clicking submit button at question form
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
								let i = 0;
								// change invalid feedback text
								for (const key in result) {
									$(inputClassName[i++]).text(result[key]);

									if (i == 8) {
										break;
									}
								}

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

				// When clicking cancel button at question form
				$("#question-cancel-submit").on("click", function () {
					// change name of file upload label
					$(".label-filename").text("Choose File");
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
							text: `
								The exam of this question has been completed or is on progress.
							`,
							confirmButtonColor: "#52616B",
							confirmButtonText: "Ok, got it!",
							background: "#ffffff",
						});
					} else if ($(this).data("deletable")) {
						Swal.fire({
							title: "Unable To Delete",
							icon: "error",
							text: "The exam is already locked.",
							confirmButtonColor: "#52616B",
							confirmButtonText: "Ok, got it!",
							background: "#ffffff",
						});
					} else {
						Swal.fire({
							title: "Delete This question?",
							html: `
								<span class="text-danger">
									Deleted data cannot be restored.
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

				// Delete question image
				$(document).on("click", "#btn-quest-img-del", function () {
					Swal.fire({
						title: "Delete This Image?",
						html: `
							<span class="text-danger">
								This action cannot be restored.
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
								url: `${baseURL}/admin/delete_question_image`,
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
				});

				// Lock exam
				$("#lock-exam").on("click", function () {
					Swal.fire({
						title: "Lock This Exam?",
						html: `
							<span class="text-danger">
								Mengunci exam berarti anda memperbolehkan user untuk mendaftar
								pada exam ini. Setelah mengunci, anda tidak dapat menambah
								maupun menghapus pertanyaan. Aksi ini tidak dapat dikembalikan.
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
							$.ajax({
								url: `${baseURL}/admin/lock_exam`,
								method: "POST",
								data: { exam: exam, action: "lock_exam" },
								dataType: "JSON",
								success: function (locked) {
									if (locked) {
										Swal.fire({
											title: "Process Success",
											icon: "success",
											text: "Berhasil mengunci exam.",
											confirmButtonColor: "#52616B",
											confirmButtonText: "Ok, got it!",
											background: "#ffffff",
											allowOutsideClick: false,
											allowEscapeKey: false,
										}).then((result) => {
											if (result.isConfirmed) {
												location.reload();
											}
										});
									} else {
										Swal.fire({
											title: "Process Failed",
											icon: "error",
											text: "Gagal mengunci exam.",
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
						}
					});
				});
			},
			error: function (err) {
				console.log(err);
			},
		});
	}
});
