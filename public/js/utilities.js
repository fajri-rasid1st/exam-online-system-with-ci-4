// Set base URL
const baseURL = "http://localhost:8080";

// Preview image when updating profile
function showPreviewProfilePict() {
	const profilePict = document.querySelector("#profile_pict");
	const previewPict = document.querySelector("#preview-img");

	$(".label-text").text(profilePict.files[0].name);

	const fileUpload = new FileReader();

	fileUpload.readAsDataURL(profilePict.files[0]);

	fileUpload.onload = function (e) {
		previewPict.src = e.target.result;
	};
}

// Alert for edit, delete, and update.
document.querySelectorAll(".flash-data").forEach((e) => {
	if (e.dataset.flash) {
		const flashData = e.dataset.flash;
		const flashTitle = e.dataset.title;
		const flashIcon = e.dataset.icon;

		Swal.fire({
			title: flashTitle,
			icon: flashIcon,
			html: flashData,
			confirmButtonColor: "#52616B",
			confirmButtonText: "Ok, got it!",
			background: "#ffffff",
		});
	}
});

// Set current value in slider (exam)
$("#duration").on("input", function () {
	$(".duration-value").text($("#duration").val());
});

$("#question").on("input", function () {
	$(".question-value").text($("#question").val());
});

$("#right-answer").on("input", function () {
	$(".right-answer-value").text($("#right-answer").val());
});

$("#wrong-answer").on("input", function () {
	$(".wrong-answer-value").text($("#wrong-answer").val());
});

$("#empty-answer").on("input", function () {
	$(".empty-answer-value").text($("#empty-answer").val());
});
