<?php
/**
 * Cabeçalho e Estrutura Base HTML (Template Header)
 * 
 * Arquivo incluído no topo de todas as páginas.
 * Contém:
 * - Declaração HTML básica
 * - Navegação do site
 * - Scripts de funcionalidades globais (modal, máscaras, etc)
 * 
 * OBS: Não feche a tag <main> aqui! Cada página fecha seu próprio HTML.
 */

require_once __DIR__ . '/functions.php';

// Recupera mensagem flash se existir
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Watch</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div>
                <h1>Tech Watch</h1>
                <p>Seja bem-vindo à Tech Watch!</p>
            </div>
            <?php if (is_logged_in()): ?>
                <div class="user-info">
                    <span>Olá, <?= esc($_SESSION['user']['email']) ?> (<?= esc($_SESSION['user']['classe']) ?>)</span>
                    <a href="logout.php" class="button secondary">Logout</a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <nav class="main-nav">
        <div class="container">
            <?php if (is_logged_in()): ?>
                <a href="index.php">Relógios</a>
                <a href="create.php">Novo Relógio</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <main class="container">
        <?php if ($flash): ?>
            <div class="flash <?= esc($flash['type']) ?>"> <?= esc($flash['message']) ?> </div>
        <?php endif; ?>

    <!-- Modal de Exclusão -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h3>Confirmar Exclusão</h3>
            <p>Tem certeza que deseja excluir este relógio? Esta ação não pode ser desfeita.</p>
            <div class="modal-actions">
                <button type="button" class="button secondary" onclick="closeDeleteModal()">Cancelar</button>
                <a id="deleteLink" href="#" class="button danger">Excluir</a>
            </div>
        </div>
    </div>

    <script>
        /**
         * FUNCIONALIDADES JAVASCRIPT GLOBAIS
         */

        /**
         * Abre o modal de confirmação de exclusão
         * @param {number} id ID do relógio a ser deletado
         */
        function openDeleteModal(id) {
            const deleteLink = document.getElementById('deleteLink');
            deleteLink.href = 'delete.php?id=' + id;
            document.getElementById('deleteModal').classList.add('active');
        }

        /**
         * Fecha o modal de exclusão
         */
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }

        // Fechar modal ao clicar fora (no overlay)
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                closeDeleteModal();
            }
        });

        /**
         * MÁSCARA MONETÁRIA BRL
         * 
         * Formata automaticamente o valor de entrada no padrão brasileiro:
         * - Aplicado a inputs com data-mask="currency"
         * - Converte para número antes de enviar o formulário
         */
        document.addEventListener('DOMContentLoaded', function() {
            const precoInputs = document.querySelectorAll('[data-mask="currency"]');
            
            precoInputs.forEach(input => {
                // Listener para formatação durante a digitação
                input.addEventListener('input', function(e) {
                    // Remove todos os caracteres não-numéricos
                    let value = e.target.value.replace(/\D/g, '');
                    if (value === '') {
                        e.target.value = '';
                        return;
                    }
                    
                    // Converte para float e formata no padrão brasileiro (pt-BR)
                    value = (parseInt(value) / 100).toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    e.target.value = value;
                });

                // Listener para converter valor formatado para número antes de enviar
                input.form.addEventListener('submit', function(e) {
                    // Remove pontos (milhares) e converte vírgula em ponto (decimal)
                    const numericValue = parseFloat(input.value.replace(/\./g, '').replace(',', '.'));
                    if (isNaN(numericValue) || numericValue <= 0) {
                        e.preventDefault();
                        alert('Por favor, insira um preço válido.');
                        return;
                    }
                    // Envia como número puro para o servidor
                    input.value = numericValue;
                });
            });
        });
    </script>
