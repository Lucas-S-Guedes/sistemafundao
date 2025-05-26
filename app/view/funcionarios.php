<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Pagamentos de Funcionários</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
           background-image: url(..app/imagens/canos.jpeg);
        }
        input[type="number"],
        input[type="date"] {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;
        }

        button {
            background-color: #4CAF50;
            /* Verde */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
            /* Verde mais escuro */
        }


        h3 {
            text-align: center;
        }



        /* Modal styles */
        #modal {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        #modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            width: 300px;
            z-index: 1001;
        }

        /* Estilo para o cabeçalho fixo com o menu de navegação */
        header {
            background-color: rgba(51, 51, 51, 0.8);
            padding: 10px 0;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }
        input[type="text"], input[type="number"], select {
    max-width: 50%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
}
form {
    background-color: #fff;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}
        .navbar {
            display: flex;
            justify-content: center;
        }

        .menu {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .menu li {
            margin: 0;
        }

        .menu a {
            color: #fff;
            text-decoration: none;
            padding: 15px 20px;
            display: block;
            transition: background-color 0.3s, color 0.3s;
        }

        .menu a:hover {
            background-color: #4CAF50;
            color: #fff;
        }

        a {
            font-family: Arial, Helvetica, sans-serif;
        }

        #calendar {
            margin: 40px auto;
            width: 1000px;
        }
        th {
    background-color: #4CAF50;
    color: white;
}
td{
    color: green;
    background-color: white;
}

    </style>
</head>
<header>
    <nav class="navbar">
        <ul class="menu">
            <li><a href="../index.php">Home</a></li>
            <li><a href="../view/viewproducts.php">Produtos</a></li>
            <li><a href="orcamento.php">Orçamento</a></li>
            <li><a href="funcionarios.php">Planejamento</a></li>
            <li><a href="../logout.php">Sair</a></li>
        </ul>
    </nav>
</header>

<body class="index-background">
    <!-- Modal para adicionar e remover lembretes -->
    <div id="modal">
        <div id="modal-content">
            <h3>Gerenciar Lembrete</h3>
            <form id="lembrete-form">
                <label for="lembrete">Lembrete:</label>
                <input type="text" id="lembrete" required><br><br>
                <label for="data-lembrete">Data:</label>
                <input type="date" id="data-lembrete" required><br><br>
                <button type="submit">Salvar Lembrete</button>
            </form>
            <br>
            <button id="remover-btn" style="display: none;">Remover Lembrete</button>

        </div>
    </div>
    <button id="limpar-btn" style="margin-top: 100px; display: flex; margin-bottom:0px;">Limpar Calendário</button>
    <div id="calendar"></div>

    <!-- Inputs para adicionar lucratividade e gastos -->
    <div>
       <br> <label for="lucro-input">Lucro do Mês:</label><br>
        <input type="number" id="lucro-input" placeholder="Digite o lucro"><br>

         <label for="gasto-input">Gasto do Mês:</label><br>
        <input type="number" id="gasto-input" placeholder="Digite o gasto">

        <br> <label for="gasto-input">Data</label><br>
        <input type="date" id="date-input">

        <button id="adicionar-btn">Adicionar</button>
        <button id="limpar-grafico-btn">Limpar Gráfico</button>

    </div>

    <canvas id="lucratividadeChart" width="400" height="200"></canvas>
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var modal = document.getElementById('modal');
            var dataInput = document.getElementById('data-lembrete');
            var lembreteInput = document.getElementById('lembrete');
            var removerBtn = document.getElementById('remover-btn');
            var limparBtn = document.getElementById('limpar-btn');
            var lembreteId; // Variável para armazenar o ID do lembrete selecionado

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                events: '../controller/load-lembretes.php',
                dateClick: function (info) {
                    modal.style.display = 'flex';
                    dataInput.value = info.dateStr;
                    lembreteInput.value = ''; // Limpa o campo de lembrete
                    removerBtn.style.display = 'none'; // Oculta o botão de remover ao adicionar um novo lembrete
                    lembreteId = null; // Reseta o ID do lembrete
                },
                eventClick: function (info) {
                    modal.style.display = 'flex';
                    lembreteId = info.event.id; // Captura o ID do evento (lembrete)
                    lembreteInput.value = info.event.title; // Exibe o lembrete atual no campo de texto
                    dataInput.value = info.event.startStr; // Exibe a data do lembrete
                    removerBtn.style.display = 'inline'; // Exibe o botão de remover
                },


            });

            calendar.render();

            // Enviar o lembrete via AJAX (Adicionar ou Atualizar)
            document.getElementById('lembrete-form').addEventListener('submit', function (e) {
                e.preventDefault();

                var lembrete = lembreteInput.value;
                var data = dataInput.value;
                var xhr = new XMLHttpRequest();
                var url = lembreteId ? '../controller/update-lembrete.php' : '../controller/add-lembrete.php';

                xhr.open('POST', url, true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (this.status == 200) {
                        alert(this.responseText);
                        modal.style.display = 'none';
                        calendar.refetchEvents(); // Atualiza o calendário
                    }
                };
                xhr.send('lembrete=' + encodeURIComponent(lembrete) + '&data=' + encodeURIComponent(data) + (lembreteId ? '&id=' + encodeURIComponent(lembreteId) : ''));
            });

            // Remover o lembrete via AJAX
            removerBtn.addEventListener('click', function () {
                var lembrete = lembreteInput.value; // Captura o texto do lembrete
                var data = dataInput.value; // Captura a data

                if (lembrete && data) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '../controller/remove-lembrete.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onload = function () {
                        if (this.status == 200) {
                            alert(this.responseText);
                            modal.style.display = 'none';
                            calendar.refetchEvents(); // Atualiza o calendário após a remoção
                        }
                    };
                    xhr.send('lembrete=' + encodeURIComponent(lembrete) + '&data=' + encodeURIComponent(data));
                } else {
                    console.log("Lembrete ou data não informados."); // Debug
                }
            });

            // Limpar todos os lembretes
            limparBtn.addEventListener('click', function () {
                if (confirm("Você tem certeza que deseja limpar todos os lembretes?")) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '../controller/limpar-lembretes.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onload = function () {
                        if (this.status == 200) {
                            alert(this.responseText);
                            calendar.refetchEvents(); // Atualiza o calendário após a limpeza
                        }
                    };
                    xhr.send(); // Não precisa enviar parâmetros, apenas acionar a limpeza
                }
            });

            // Fechar o modal se clicar fora
            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            };
        }
        );

        // Dados para o gráfico (substitua pelos dados reais da sua aplicação)
        const labels = [];
        const lucros = []; // Lucratividade
        const gastos = []; // Gastos/Perdas
        const date = [];

        const ctx = document.getElementById('lucratividadeChart').getContext('2d');
        const lucratividadeChart = new Chart(ctx, {
            type: 'bar', // ou 'line', 'pie', etc.
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Lucratividade',
                        data: lucros,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Gastos/Perdas',
                        data: gastos,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        // Adicionar dados ao gráfico e salvar no banco
        document.getElementById('adicionar-btn').addEventListener('click', function () {
            const lucroInput = document.getElementById('lucro-input').value;
            const gastoInput = document.getElementById('gasto-input').value;
            const date = document.getElementById('date-input').value;

            if (lucroInput && gastoInput && date) {
                lucros.push(parseFloat(lucroInput));
                gastos.push(parseFloat(gastoInput));
                labels.push(date);

                // Salvar no banco de dados
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '../controller/lucro-gasto.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (this.status == 200) {
                        alert(this.responseText);
                        lucratividadeChart.update(); // Atualiza o gráfico
                    }
                };
                xhr.send('data=' + encodeURIComponent(date) + '&lucro=' + encodeURIComponent(lucroInput) + '&gasto=' + encodeURIComponent(gastoInput));

                document.getElementById('lucro-input').value = ''; // Limpa o input
                document.getElementById('gasto-input').value = '';
                document.getElementById('date-input').value = '';
            } else {
                alert("Por favor, preencha todos os campos.");
            }
        });
        // Função para carregar os dados do banco e atualizar o gráfico
        function carregarDados() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '../controller/carregar-lucros-gastos.php', true);
            xhr.onload = function () {
                if (this.status == 200) {
                    const dados = JSON.parse(this.responseText);
                    dados.forEach(item => {
                        lucros.push(parseFloat(item.lucro));
                        gastos.push(parseFloat(item.gasto));
                        labels.push(item.data);
                    });
                    lucratividadeChart.update(); // Atualiza o gráfico com os dados do banco
                }
            };
            xhr.send();
        }

        // Chamar a função para carregar os dados ao inicializar a página
        document.addEventListener('DOMContentLoaded', function () {
            carregarDados();
        });
        document.getElementById('limpar-grafico-btn').addEventListener('click', function () {
            if (confirm("Você tem certeza que deseja limpar todos os dados do gráfico?")) {
                // Limpar os dados do gráfico
                lucros.length = 0; // Limpa os dados de lucratividade
                gastos.length = 0; // Limpa os dados de gastos
                labels.length = 0; // Limpa os labels do gráfico

                lucratividadeChart.update(); // Atualiza o gráfico

                // Enviar requisição para limpar os dados no banco
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '../controller/limpar-lucros-gastos.php', true);
                xhr.onload = function () {
                    if (this.status == 200) {
                        alert(this.responseText); // Exibe a resposta do servidor
                    }
                };
                xhr.send();
            }
        });




    </script>
</body>

</html>