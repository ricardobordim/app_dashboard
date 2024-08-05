<?php
// Uma classe dashboard
class Dashboard {
    public $dataInicio;
    public $dataFim;
    public $numeroVendas;
    public $totalVendas;

    public function __get($atributo){
        return $this->$atributo;

    }
    public function __set($atributo,$valor){
        $this->$atributo = $valor;
    }

}

// classe de conexa com o banco

class Conexao {
    private $host = 'localhost';
    private $dbname = 'dashboard';
    private $user = 'root';
    private $pass = '';

    public function conectar(){
        try {
            $conexao = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname",
                "$this->user",
                "$this->pass"
            );

            // Usar a mesma coleção de dados no front e back
            // tendo os mesmos tipos de caracteres em toda a comunicação
            $conexao->exec('set charset utf8');

            return $conexao;

        } catch (PDOException $e) {
            echo '<p>' . $e->getMessage() . '</p>';
        }
    }
}

// classe semelhante a um model

class Bd{
    private $conexao;
    private $dashboard;

    public function __construct(Conexao $conexao,Dashboard $dashboard){
        $this->conexao = $conexao->conectar();
        $this->dashboard = $dashboard;
    }

    public function getNumeroVendas(){
        $query = 'select count(*) as numero_vendas from tb_vendas where data_venda BETWEEN :data_inicio and :data_fim';
        $stmt = $this->conexao->prepare($query);
        // $stmt->bindValue('data_inicio','2018-08-01');
        $stmt->bindValue('data_inicio', $this->dashboard->__get('dataInicio'));
        
        // $stmt->bindValue('data_fim', '2018-08-31');
        $stmt->bindValue('data_fim',$this->dashboard->__get('dataFim'));


        $stmt->execute();


        // return $stmt->fetch(PDO::FETCH_OBJ);
        return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;


    }


    public function getTotalVendas()
    {
        $query = 'select  sum(total) as  total_vendas from tb_vendas where data_venda BETWEEN :data_inicio and :data_fim';
        $stmt = $this->conexao->prepare($query);
        // $stmt->bindValue('data_inicio','2018-08-01');
        $stmt->bindValue('data_inicio', $this->dashboard->__get('dataInicio'));

        // $stmt->bindValue('data_fim', '2018-08-31');
        $stmt->bindValue('data_fim', $this->dashboard->__get('dataFim'));


        $stmt->execute();


        // return $stmt->fetch(PDO::FETCH_OBJ);
        return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
    }

}

// Juntando tudo com a lógica do script

$dashboard = new Dashboard();
$conexao = new Conexao();

$competencia = explode('-',$_GET['competencia']);
$ano = $competencia[0];
$mes = $competencia[1];

$dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);


// $dashboard->__set('dataInicio','2018-10-01');
$dashboard->__set('dataInicio', $ano . '-' . $mes . '-' . '01');

// $dashboard->__set('dataFim', '2018-10-31');
$dashboard->__set('dataFim', $ano . '-' . $mes . '-' . $dias_do_mes);



$bd = new Bd($conexao, $dashboard);

$dashboard->__set('numeroVendas', $bd->getNumeroVendas());
$dashboard->__set('totalVendas', $bd->getTotalVendas());


// print_r($bd->getNumeroVendas());

// acompanhando a evolução do Dashboard
// print_r($dashboard);

// print_r($_GET);

// print_r($competencia);

// print_r($ano .'/'.$mes.'/'.$dias_do_mes);


// print_r($dashboard);


echo json_encode($dashboard);


?>
