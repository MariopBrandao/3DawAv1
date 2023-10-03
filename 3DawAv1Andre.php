<?php
// Ler arquivo Produtos.txt
$produtos = array_map('str_getcsv', file('Produtos.txt'));
array_walk($produtos, function(&$a) use ($produtos) {
  $a = array_combine($produtos[0], $a);
});
array_shift($produtos); // Tira cabeçalho

// Inicializa o carrinho de compras
if (!isset($_SESSION['carrinho'])) {
  $_SESSION['carrinho'] = [];
}

// Bota no carrinho
if (isset($_POST['adicionar'])) {
  $idProduto = $_POST['id'];
  $quantidadeProduto = $_POST['quantidade'];
  foreach ($produtos as $produto) {
    if ($produto['Id'] == $idProduto) {
      $produto['Quantidade'] = $quantidadeProduto;
      $_SESSION['carrinho'][] = $produto;
      break;
    }
  }
}

// Tira do carrinho
if (isset($_POST['excluir'])) {
  $idProduto = $_POST['id'];
  foreach ($_SESSION['carrinho'] as $chave => $produto) {
    if ($produto['Id'] == $idProduto) {
      unset($_SESSION['carrinho'][$chave]);
      break;
    }
  }
}

// Lista produto
echo "<table>";
echo "<tr><th>Id</th><th>Nome</th><th>Valor</th><th>Quantidade</th><th></th></tr>";
foreach ($produtos as $produto) {
  echo "<tr>";
  echo "<td>".$produto['Id']."</td>";
  echo "<td>".$produto['Nome']."</td>";
  echo "<td>".$produto['Valor']."</td>";
  echo "<td><input type='number' name='quantidade' min='1'></td>";
  echo "<td><button type='submit' name='adicionar' value='".$produto['Id']."'>Adicionar ao Carrinho</button></td>";
  echo "</tr>";
}
echo "</table>";

// Carrinho
echo "<h2>Carrinho de Compras</h2>";
echo "<table>";
echo "<tr><th>Id</th><th>Nome</th><th>Valor Unitário</th><th>Quantidade</th><th>Valor Total</th><th></th></tr>";
$totalCarrinho = 0;
foreach ($_SESSION['carrinho'] as $item) {
  $valorUnitario = floatval(str_replace(',', '.', str_replace('R$', '', $item['Valor'])));
  $valorTotal = $valorUnitario * intval($item['Quantidade']);
  $totalCarrinho += $valorTotal;
  
  echo "<tr>";
  echo "<td>".$item['Id']."</td>";
  echo "<td>".$item['Nome']."</td>";
  echo "<td>R$".number_format($valorUnitario, 2, ',', '.')."</td>";
  echo "<td>".$item['Quantidade']."</td>";
  echo "<td>R$".number_format($valorTotal, 2, ',', '.')."</td>";
  echo "<td><button type='submit' name='excluir' value='".$item['Id']."'>Excluir</button></td>";
  echo "</tr>";
}
echo "</table>";

echo "<h3>Total do Carrinho: R$".number_format($totalCarrinho, 2, ',', '.')."</h3>";

?>
