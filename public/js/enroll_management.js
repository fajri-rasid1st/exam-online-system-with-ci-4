jQuery(function () {
	if (code) {
		if (page == "user_enroll") {
			// Get current exam
			$.ajax({
				url: `${baseURL}/admin/get_current_exam`,
				method: "POST",
				data: { code: code },
				dataType: "JSON",
				success: function (exam) {
					// Change name of table title
					$(".table-title").text(`User Enroll Table : ${exam.title}`);

					// Show users enroll table
					$("#users-enroll-table").DataTable({
						serverSide: true,
						processing: true,
						order: [],
						ajax: {
							url: `${baseURL}/admin/fetch_all_users_enroll`,
							type: "POST",
							data: { id: exam.id },
							error: function (err) {
								console.log(err);
							},
						},
						columnDefs: [
							{
								targets: [1],
								orderable: false,
							},
							{
								targets: [6],
								orderable: false,
							},
							{
								targets: [7],
								orderable: false,
							},
						],
					});
				},
				error: function (err) {
					console.log(err);
				},
			});

			// Delete user enrolled exam
			$(document).on("click", "#user-enroll-delete", function () {
				if ($(this).data("deletable")) {
					Swal.fire({
						title: "Can't Delete User",
						icon: "error",
						text: `
                        	Can't delete user when exam has been completed or on progress.
						`,
						confirmButtonColor: "#52616B",
						confirmButtonText: "Ok, got it!",
						background: "#ffffff",
					});
				} else {
					Swal.fire({
						title: "Hapus User Ini?",
						html: `
							<span class="text-danger">
								Jika iya, user akan dikeluarkan dari exam ini.
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
								url: `${baseURL}/admin/user_enroll_delete`,
								method: "POST",
								data: {
									enroll_id: $(this).data("id"),
									exam_id: $(this).data("examid"),
									user_id: $(this).data("userid"),
									action: "cancel_exam",
								},
								success: function (result) {
									Swal.fire({
										title: "Berhasil",
										icon: "success",
										text: result,
										confirmButtonColor: "#52616B",
										confirmButtonText: "Ok, got it!",
										background: "#ffffff",
									});

									$("#users-enroll-table")
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
		} else if (page == "user_exam_result") {
			// Get current exam
			$.ajax({
				url: `${baseURL}/admin/get_current_exam`,
				method: "POST",
				data: { code: code, user: user },
				dataType: "JSON",
				success: function (exam) {
					// Change name of table title
					$(".table-title").text(
						`Result For :  ${exam.user_result} at ${exam.title}`
					);

					// Show user exam result table
					$("#user-exam-result-table").DataTable({
						serverSide: true,
						processing: true,
						order: [],
						ajax: {
							url: `${baseURL}/admin/fetch_user_exam_result`,
							type: "POST",
							data: { exam_id: exam.id, user_id: user },
							error: function (err) {
								console.log(err);
							},
						},
						columnDefs: [
							{
								targets: [0],
								orderable: false,
							},
							{
								targets: [5],
								orderable: false,
							},
						],
					});
				},
				error: function (err) {
					console.log(err);
				},
			});
		} else if (page == "admin_exam_result") {
			// Get current exam
			$.ajax({
				url: `${baseURL}/admin/get_current_exam`,
				method: "POST",
				data: { code: code },
				dataType: "JSON",
				success: function (exam) {
					// Change name of table title
					$(".table-title").text(`Exam Result : ${exam.title}`);

					// Show user exam result table
					$("#admin-exam-result-table").DataTable({
						serverSide: true,
						processing: true,
						order: [],
						ajax: {
							url: `${baseURL}/admin/fetch_admin_exam_result`,
							type: "POST",
							data: { exam_id: exam.id },
							error: function (err) {
								console.log(err);
							},
						},
						columnDefs: [
							{
								targets: [1],
								orderable: false,
							},
						],
					});
				},
				error: function (err) {
					console.log(err);
				},
			});
		}
	}
});
