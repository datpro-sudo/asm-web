document.addEventListener("DOMContentLoaded", async () => {
  const productList = document.getElementById("product-list");

  try {
    const response = await fetch("load_products.php");
    const products = await response.json();

    products.forEach((product) => {
      const productHTML = `
                <div class="product">
                    <img src="images/${product.product_image}" alt="${
        product.product_name
      }">
                    <h3>${product.product_name}</h3>
                    <p>Price: ${Number(
                      product.product_price
                    ).toLocaleString()}đ</p>
                    <button class="btn-details"  onclick="viewDetails(${
                      product.product_id
                    })">View Details</button>
                    <button class="btn-add-to-cart" onclick="addToCart(${
                      product.product_id
                    })">Add to Cart</button>
                </div>`;
      productList.innerHTML += productHTML;
    });
  } catch (error) {
    console.error("Error loading products:", error);
  }
});

async function viewDetails(productId) {
  try {
    const response = await fetch(`product_detail.php?id=${productId}`);
    const product = await response.json();

    document.getElementById(
      "modal-image"
    ).src = `images/${product.product_image}`;
    document.getElementById("modal-title").textContent = product.product_name;
    document.getElementById("modal-description").textContent =
      product.product_description;
    document.getElementById("modal-price").textContent = `Price: ${Number(
      product.product_price
    ).toLocaleString()}đ`;

    const addToCartBtn = document.getElementById("add-to-cart");
    addToCartBtn.onclick = () => addToCart(product.product_id);

    document.getElementById("modal-container").style.display = "flex";
  } catch (error) {
    console.error("Error fetching product details:", error);
  }
}

async function addToCart(productId, quantity = 1) {
  try {
    const response = await fetch("add_to_cart.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `product_id=${productId}&quantity=${quantity}`,
    });

    const result = await response.json();
    if (result.status === "success") {
      alert("Product added to cart successfully!");
    } else {
      alert("Failed to add product to cart: " + result.message);
    }
  } catch (error) {
    console.error("Error adding product to cart:", error);
  }
}

function closeModal() {
  document.getElementById("modal-container").style.display = "none";
}
