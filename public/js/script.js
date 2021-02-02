jQuery(function () {
	// enroll exam action
	$(document).on("click", "#btn-enroll", function () {
		Swal.fire({
			title: "Anda Yakin Ingin Mendaftar?",
			html: `
				<span class="text-danger">
					Setelah terdaftar, anda tidak dapat membatalkan pendaftaran.
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
				const userId = $(this).data("userid");
				const examId = $(this).data("examid");

				$.ajax({
					url: `${baseURL}/user/enroll_exam`,
					method: "POST",
					data: { exam_id: examId, action: "enroll_exam" },
					dataType: "JSON",
					beforeSend: function () {
						$("#btn-enroll").attr("disabled", "disabled");
						$("#btn-enroll").text("Tunggu Sebentar...");
					},
					success: function (enrolled) {
						if (enrolled) {
							$("#btn-enroll").text("Pendaftaran Berhasil");

							Swal.fire({
								title: "Pendaftaran Berhasil",
								icon: "success",
								text: `Berhasil mendaftar. Silahkan cek list try out anda.`,
								confirmButtonColor: "#52616B",
								confirmButtonText: "Ok, got it!",
								background: "#ffffff",
							}).then((result) => {
								if (result.isConfirmed) {
									document.location.href = baseURL;
								}
							});
						} else {
							Swal.fire({
								title: "Pendaftaran Gagal",
								icon: "error",
								text: `Anda harus melengkapi data diri terlebih dahulu.`,
								confirmButtonColor: "#52616B",
								confirmButtonText: "Ok, got it!",
								background: "#ffffff",
							}).then((result) => {
								if (result.isConfirmed) {
									document.location.href = `${baseURL}/user/${userId}`;
								}
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

	// show/hide user enrolled exam detail
	$(document).on("click", "#btn-detail-enroll-exam", function () {
		const id = $(this).data("id");
		const detail = $(`#detail-enroll-exam-${id}`);
		const text = $(this).text().trim().toLowerCase();

		$(this).text(
			text == "lihat detail" ? "Sembunyikan Detail" : "Lihat Detail"
		);

		detail.slideToggle(400);
	});

	// function to load question
	function loadQuestion(examCode, questionId) {
		$.ajax({
			url: `${baseURL}/user/load_question`,
			method: "POST",
			data: {
				exam_code: examCode,
				question_id: questionId,
				action: "load_question",
			},
			success: function (result) {
				$("#single-question-area").html(result);

				const radioOptions = $(".radio-option");

				radioOptions.each(function () {
					if ($(this).attr("checked")) {
						$(this).parent("div").toggleClass("btn-dark");
					}
				});
			},
			error: function (err) {
				console.log(err);
			},
		});
	}

	// function for navigation question
	function questionNav(examCode) {
		$.ajax({
			url: `${baseURL}/user/question_nav`,
			method: "POST",
			data: {
				exam_code: examCode,
				action: "question_nav",
			},
			success: function (result) {
				$("#nav-question-area").html(result);
			},
			error: function (err) {
				console.log(err);
			},
		});
	}

	// if code has been sended, this code will be execute when page in exam view
	if (code && page == "user_exam_view") {
		// call load question function
		loadQuestion(code, 0);

		// call question navigation function
		questionNav(code);

		// next question button
		$(document).on("click", ".btn-next", function () {
			const questionId = $(this).data("id");
			loadQuestion(code, questionId);
		});

		// previous question button
		$(document).on("click", ".btn-prev", function () {
			const questionId = $(this).data("id");
			loadQuestion(code, questionId);
		});

		// navigation question button
		$(document).on("click", "#question-nav", function () {
			const questionId = $(this).data("id");
			loadQuestion(code, questionId);
		});

		// handle option radio button
		$(document).on("click", ".opt-label", function () {
			$(".option-container").removeClass("btn-dark");
			$(this).parent("div").toggleClass("btn-dark");

			const optionSelected = $(this).prev("input");
			const questionId = optionSelected.data("questionid");

			$.ajax({
				url: `${baseURL}/user/user_answer`,
				method: "POST",
				data: {
					exam_code: code,
					question_id: questionId,
					answer_option: optionSelected.val(),
					action: "user_answer",
				},
			});
		});

		// exam timer
		$(".exam-timer").TimeCircles({
			circle_bg_color: "#AEAFBA",
			direction: "Counter-clockwise",
			time: {
				Days: { show: false },
				Hours: { color: "#f6c23e" },
				Minutes: { color: "#2de327" },
				Seconds: { color: "#f51414" },
			},
		});

		const timer = setInterval(() => {
			let remaining_sec = $(".exam-timer").TimeCircles().getTime();

			if (remaining_sec < 1) {
				$(".exam-timer").TimeCircles().stop();

				Swal.fire({
					title: "Waktu Habis!",
					icon: "info",
					text: `Terima kasih telah berpartisipasi pada Try Out kali ini.`,
					confirmButtonColor: "#52616B",
					confirmButtonText: "Kembali",
					background: "#ffffff",
					allowOutsideClick: false,
					allowEscapeKey: false,
				}).then((result) => {
					if (result.isConfirmed) {
						document.location.href = baseURL;
					}
				});

				clearInterval(timer);
			}
		}, 1000);
	}
});
