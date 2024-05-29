<?php
    include 'registro.php'; 
    class RegistroService
    {
        public function get($id = null)
        {
            if ($id){
                return Registro::select($id) ;
            }else{
                return Registro::selectAll() ;
            }
        }
        public function post()
        {
            $dados = json_decode(file_get_contents('php://input'), true, 512);
            if ($dados == null){
                throw new Exception("Falta os dados para incluir!");
            }
            return Registro::insert($dados);
        }
        public function delete($id = null)
        {
            if ($id === null){
                throw new Exception("Falta o código!");
        }
        return Registro::delete($id);
        }
        public function put($id = null)
        {
            if ($id == null)
            {
                throw new Exception("falta o codigo!!");
            }
            $dados = json_decode(file_get_contents('php://input'), true, 512);
            if ($dados == null)
            {
                throw new Exception("Falta informação!");
            }
            return Registro::update($id,$dados);
        }
    }
?>