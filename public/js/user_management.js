// -------------------- Admin Side --------------------

jQuery(function () {
	// Show Users Table (admin)
	$("#users-table").DataTable({
		serverSide: true,
		processing: true,
		order: [],
		ajax: {
			url: `${baseURL}/admin/fetch_all_users`,
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
		],
	});

	// Edit User (admin)
	$(document).on("click", "#btn-edit", function () {
		const id = $(this).data("id");

		$.ajax({
			url: `${baseURL}/admin/fetch_single_user`,
			method: "POST",
			data: { id: id },
			dataType: "JSON",
			success: function (data) {
				// fill input with existing data
				$(".profile-pict-edit").attr(
					"src",
					`${baseURL}/img/profile/${data.profile_pict}`
				);
				$("#username").val(data.username);
				$("#email").val(data.email);
				$("#fullname").val(data.fullname);
				$("#phone_number").val(data.phone_number);
				$("#gender").val(data.gender);
				$("#address").val(data.address);

				// change invalid feedback text
				$(".invalid-username").text("");
				$(".invalid-email").text("");
				$(".invalid-fullname").text("");
				$(".invalid-phone_number").text("");
				$(".invalid-gender").text("");
				$(".invalid-address").text("");

				// show modal
				$("#editModal").modal("show");

				// set value input named hiddenid
				$("#hidden-id").val(id);
			},
			error: function (err) {
				console.log(err);
			},
		});
	});

	// When clicking submit button at edit form, then (admin)
	$("#edit-user-form").on("submit", function (e) {
		e.preventDefault();

		$.ajax({
			url: `${baseURL}/admin/update`,
			method: "POST",
			data: $(this).serialize(),
			dataType: "JSON",
			beforeSend: function () {
				$("#admin-user-submit").html("Wait...");
				$("#admin-user-submit").attr("disabled", "disabled");
			},
			success: function (data) {
				$("#admin-user-submit").html("Save Changes");
				$("#admin-user-submit").attr("disabled", false);

				if (data.error == "yes") {
					// change invalid feedback text
					$(".invalid-username").text(data.invalidUsername);
					$(".invalid-email").text(data.invalidEmail);
					$(".invalid-fullname").text(data.invalidFullname);
					$(".invalid-phone_number").text(data.invalidPhonenumber);
					$(".invalid-gender").text(data.invalidGender);
					$(".invalid-address").text(data.invalidAddress);

					// change color border input to red if validation has error
					if (data.invalidUsername == "") {
						$(".invalid-username")
							.prev("input")
							.removeClass("is-invalid");
					} else {
						$(".invalid-username")
							.prev("input")
							.addClass("is-invalid");
					}

					if (data.invalidEmail == "") {
						$(".invalid-email")
							.prev("input")
							.removeClass("is-invalid");
					} else {
						$(".invalid-email")
							.prev("input")
							.addClass("is-invalid");
					}

					if (data.invalidFullname == "") {
						$(".invalid-fullname")
							.prev("input")
							.removeClass("is-invalid");
					} else {
						$(".invalid-fullname")
							.prev("input")
							.addClass("is-invalid");
					}

					if (data.invalidPhonenumber == "") {
						$(".invalid-phone_number")
							.prev("input")
							.removeClass("is-invalid");
					} else {
						$(".invalid-phone_number")
							.prev("input")
							.addClass("is-invalid");
					}

					if (data.invalidGender == "") {
						$(".invalid-gender")
							.prev("select")
							.removeClass("is-invalid");
					} else {
						$(".invalid-gender")
							.prev("select")
							.addClass("is-invalid");
					}

					if (data.invalidAddress == "") {
						$(".invalid-address")
							.prev("input")
							.removeClass("is-invalid");
					} else {
						$(".invalid-address")
							.prev("input")
							.addClass("is-invalid");
					}
				} else {
					// hide modal
					$("#editModal").modal("hide");

					// success alert message for edited data
					Swal.fire({
						title: "Edited",
						icon: "success",
						text: data.message,
						confirmButtonColor: "#52616B",
						confirmButtonText: "Ok, got it!",
						background: "#ffffff",
					});

					// reload DataTable
					$("#users-table").DataTable().ajax.reload();

					// reset invalid input border color
					$(".invalid-email").prev("input").removeClass("is-invalid");
					$(".invalid-username")
						.prev("input")
						.removeClass("is-invalid");
					$(".invalid-fullname")
						.prev("input")
						.removeClass("is-invalid");
					$(".invalid-phone_number")
						.prev("input")
						.removeClass("is-invalid");
					$(".invalid-gender")
						.prev("select")
						.removeClass("is-invalid");
					$(".invalid-address")
						.prev("input")
						.removeClass("is-invalid");
				}
			},
			error: function (err) {
				console.log(err);
			},
		});
	});

	// When clicking cancel button at edit form, then (admin)
	$("#admin-cancel-submit").on("click", function () {
		// reset invalid input border color
		$(".invalid-username").prev("input").removeClass("is-invalid");
		$(".invalid-email").prev("input").removeClass("is-invalid");
		$(".invalid-fullname").prev("input").removeClass("is-invalid");
		$(".invalid-phone_number").prev("input").removeClass("is-invalid");
		$(".invalid-gender").prev("select").removeClass("is-invalid");
		$(".invalid-address").prev("input").removeClass("is-invalid");
	});

	// Delete User (admin)
	$(document).on("click", "#btn-delete", function () {
		Swal.fire({
			title: "Delete This User?",
			text: "Data that you delete cannot be restored.",
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
					url: `${baseURL}/admin/delete`,
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
						$("#users-table").DataTable().ajax.reload();
					},
					error: function (err) {
						console.log(err);
					},
				});
			}
		});
	});
});