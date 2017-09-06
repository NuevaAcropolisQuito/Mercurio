<?phpif (!defined('BASEPATH'))    exit('No direct script access allowed');class Suscriptores_model extends CI_Model {    public function __construct() {        parent::__construct();    }    public function index_mdl() {        $w_buscar = $this->input->post('w_buscar');        $desde = $this->input->post('desde');        if ($desde == "")            $desde = 0;        $paginado = $html = "";        $cuantos = cuantosResultados();                if (strlen($w_buscar) > 3) {            $campos = "`sus_nombres`,`sus_apellidos`,`sus_email`";            $where = "WHERE MATCH ($campos) AGAINST ('$w_buscar' IN BOOLEAN MODE)";            $order_by = "ORDER BY `puntos` DESC";            $sql = "SELECT *, MATCH($campos) AGAINST ('$w_buscar') AS 'puntos' from `suscriptores` $where $order_by";        } else            $sql = "SELECT * FROM `suscriptores`";        $paginado = paginador_multiple($sql, $cuantos, $desde);        $desde*=$cuantos;        $sql_limit = $sql . " limit $desde,$cuantos";        $query = $this->db->query($sql_limit);        if ($query->num_rows() > 0) {            $html = "";            foreach ($query->result() as $fila) {                $html.= $this->parser->parse('admin/suscriptores/index_tpl', $fila, TRUE);            }        } else {            $html.="<tr><td colspan='5'>No hay suscriptores (<strong>$w_buscar</strong>)</td></tr>";        }        $arreglo["w_buscar"] = $w_buscar;        $arreglo["html"] = $html;        $arreglo["paginado"] = $paginado;        return $arreglo;    }    public function almacenar_mdl() {        $hoy = hoy('c');        $arreglo = array(            "sus_nombres" => $this->input->post('sus_nombres'),            "sus_apellidos" => $this->input->post('sus_apellidos'),            "sus_email" => $this->input->post('sus_email'),            "sus_celular" => $this->input->post('sus_celular'),            "sus_fecha" => $hoy,        );        $this->db->insert('suscriptores', $arreglo);        $sus_id = $this->db->insert_id();        return $sus_id;    }    public function actualizar_mdl() {        $id = $this->input->post('sus_id');        $arreglo = array(            "sus_nombres" => $this->input->post('sus_nombres'),            "sus_apellidos" => $this->input->post('sus_apellidos'),            "sus_email" => $this->input->post('sus_email'),            "sus_celular" => $this->input->post('sus_celular'),                    );        $this->db->where('id', $id);        $this->db->update('suscriptores', $arreglo);    }    public function datos_suscriptor() {        $id = $this->uri->segment(4);        $this->db->where("id", $id);        $query = $this->db->get('suscriptores');        if ($query->num_rows() > 0)            return $query->row_array();        else            return "Sin datos ($id)";    }}