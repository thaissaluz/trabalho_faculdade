<?php

        //chamando o arquivo config.php
        include 'config.php';
	
	    //criei uma class chamada 'Registro'
	class Registro
    {
        //metodo para fazer a consulta atraves do parametro $id
        public static function select (int $id)
        {
            //criar duas variáveis para tabela e primeira coluna 
            $tabela = 'registro_ponto'; 	//variavel para nome da tabela
            $coluna = 'id_funcionario'; 	//variavel para chave primaria

            //conectando com o banco de dados através da classe (objeto)  PDO
            //pegando as informações do config.php (variaveis globais)
            $connPdo = new PDO(dbDrive. ':host='.dbHost.'; dbname='.dbName, dbUser, dbPass);

            // usando comando sql que será executado no banco de dados para consultar um determinado registro
            $sql = "select * from $tabela where $coluna = :id" ;

            //preparando o comando Select do SQL para ser executado usando o método prepare()
            $stmt =  $connPdo->prepare($sql);

            //configurando (ou mapeando) o parametro de busca
            $stmt->bindValue(':id', $id);

            //executando o comando select do SQL no banco de dados
            $stmt->execute() ;

            if ($stmt->rowCount() > 0) //se houver retorno de dados (registro)
            {
                return $stmt->fetch(PDO::FETCH_ASSOC) ;
            }else{
                throw new Exception ("Sem registro do funcionário");
            }
        }

            //2) um método para fazer consultado de todos os dados sem parametro
            public static function selectAll()
            {
                $tabela = "registro_ponto"; 

                $connPdo = new PDO(dbDrive.':host='.dbHost.'; dbname='. dbName, dbUser, dbPass);
                //criar a execuçao de  consulta usando a linguagem sql 
                $sql = "select * from $tabela" ;

                $stmt = $connPdo->prepare($sql) ;
                $stmt->execute() ;

                if ($stmt->rowCount() > 0)
                {
                    return $stmt->fetchAll(PDO::FETCH_ASSOC) ;
            }else{
                throw new Exception("Sem registros");
            }
        }
        public static function insert($dados)
        {
            $tabela = "funcionarios"; //uma variavel para nome da tabela "funcionarios"
            $tabela2 = "registro_ponto";
            $connPdo = new PDO(dbDrive.':host='.dbHost.'; dbname='.dbName, dbUser, dbPass);
            $sql = "insert into $tabela (nome,cpf) values (:nome,:cpf)";
            $stmt = $connPdo->prepare($sql);
            //Mapear os parametros para obter os dados de inclusão
            $stmt->bindValue(':nome', $dados ['nome']);
            $stmt->bindValue(':cpf', $dados['cpf']);
            $stmt->execute() ;

            if ($stmt->rowCount()>0)
            {
                $sqlRegistro = "INSERT INTO $tabela2 (id_funcionario, nome, entrada, saida, entrada_2, saida_2) VALUES (:id_funcionario, :nome, '08:00', '12:00', '13:00', '18:00')";
                $stmtRegistro = $connPdo->prepare($sqlRegistro);
                $stmtRegistro->bindValue(':nome', $dados['nome']);
                $stmtRegistro->bindValue(':id_funcionario', $connPdo->lastInsertId()); // Pega o ID do funcionário inserido anteriormente
                $stmtRegistro->execute();
            }

            if ($stmt->rowCount() > 0){
                return 'Dados cadastrados com sucesso!';
            }else{
                throw new Exception("Erro ao inserir os dados");
            }
        }
        public static function delete($id)
        {
            $tabela = "registro_ponto";
            $tabela2 = "funcionarios";
            $coluna = "id_funcionario";
            $connPdo = new PDO(dbDrive.':host='.dbHost.'; dbname='.dbName, dbUser, dbPass);
            $sql = "delete from $tabela where $coluna = :id_funcionario";
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':id_funcionario', $id) ;
            $stmt->execute() ;

            if ($stmt->rowCount() > 0)
            {
                $stmtDelete = $connPdo->prepare("delete from $tabela2 where $coluna = :id_funcionario");
                $stmtDelete->bindValue(':id_funcionario', $id);
                $stmtDelete->execute();
                return 'Dados excluidos com sucesso!' ;
            }else{
                throw new Exception("Erro ao excluir os dados");
            }
        }

        public static function update($id,$dados)
        {
            $tabela = "funcionarios";
            $tabela2 = "registro_ponto";
            $coluna = "id_funcionario";
            $connPdo = new PDO(dbDrive.':host='.dbHost.'; dbname='.dbName, dbUser, dbPass);
            $sql = "update $tabela set nome=:nome,cpf=:cpf where $coluna=:id_funcionario";
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':id_funcionario', $id);
            $stmt->bindValue(':nome', $dados['nome']);
            $stmt->bindValue(':cpf', $dados['cpf']);
            $stmt->execute();

            if($stmt->rowCount() > 0)
            {
                $stmtUp = $connPdo->prepare("update $tabela2 set nome=:nome where $coluna=:id_funcionario");
                $stmtUp->bindValue('id_funcionario', $id);
                $stmtUp->bindValue(':nome', $dados['nome']);
                $stmtUp->execute();
                return 'Dados alterados com sucesso!';
            }else{
                throw new Exception("Erro ao alterar os dados");
            }
        }
	}
?>
