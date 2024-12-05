// Gán sự kiện cho các nút "Remove"
document.querySelectorAll(".remove-btn").forEach((button) => {
  button.addEventListener("click", async function () {
    const cartId = this.getAttribute("data-cart-id");

    const response = await fetch("remove_from_cart.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `cart_id=${cartId}`,
    });

    const result = await response.json();

    if (result.status === "success") {
      // Ẩn dòng sản phẩm trong bảng
      document.getElementById("cart-item-" + cartId).style.display = "none";

      // Hiển thị modal thông báo xóa thành công
      const modal = document.getElementById("success-modal");
      const message = document.getElementById("success-message");
      message.textContent = result.message;
      modal.style.display = "flex";

      // Tự động ẩn modal sau 3 giây
      setTimeout(() => {
        modal.style.display = "none";
      }, 3000);
    } else {
      alert(result.message);
    }
  });
});
