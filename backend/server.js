const express = require("express");
const bodyParser = require("body-parser");
const fs = require("fs");
const app = express();
const PORT = process.env.PORT || 3000;

app.use(bodyParser.json());

const readProducts = () => {
  const data = fs.readFileSync("./products.json");
  return JSON.parse(data);
};

const writeProducts = (products) => {
  fs.writeFileSync("./products.json", JSON.stringify(products, null, 2));
};

app.get("/api/products", (req, res) => {
  const products = readProducts();
  res.json(products);
});

app.post("/api/products", (req, res) => {
  const products = readProducts();
  const newProduct = req.body;
  newProduct.id = products.length ? products[products.length - 1].id + 1 : 1;
  products.push(newProduct);
  writeProducts(products);
  res.status(201).json(newProduct);
});

app.put("/api/products/:id", (req, res) => {
  const products = readProducts();
  const index = products.findIndex((p) => p.id == req.params.id);
  if (index === -1) return res.status(404).send("Product not found");

  products[index] = { ...products[index], ...req.body };
  writeProducts(products);
  res.json(products[index]);
});

app.delete("/api/products/:id", (req, res) => {
  let products = readProducts();
  products = products.filter((p) => p.id != req.params.id);
  writeProducts(products);
  res.status(204).send();
});

app.get("/api/export/csv", (req, res) => {
  const products = readProducts();
  let csv = "id,name,price\n";
  products.forEach((product) => {
    csv += `${product.id},${product.name},${product.price}\n`;
  });
  res.header("Content-Type", "text/csv");
  res.attachment("products.csv");
  res.send(csv);
});

app.get("/api/export/xml", (req, res) => {
  const products = readProducts();
  let xml = '<?xml version="1.0"?>\n<products>\n';
  products.forEach((product) => {
    xml += `  <product>\n    <id>${product.id}</id>\n    <name>${product.name}</name>\n    <price>${product.price}</price>\n  </product>\n`;
  });
  xml += "</products>";
  res.header("Content-Type", "application/xml");
  res.attachment("products.xml");
  res.send(xml);
});

app.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});
