<?

class Oyente{
    private $idOyente;
    private $twitter;
    private $idPais;
    private $lat;
    private $lon;
    //Campos no visibles
    private $nombre;
    private $apellido;
    private $email;
    private $mecenas;
    private $fechaNacimiento;
    private $telefono;
    private $clave;
    private $activo;
    private $admin;

    function __construct($idOyente, $twitter, $idPais, $lat, $lon, $nombre, $apellido, $email, $mecenas, $fechaNacimiento, $telefono, $clave, $activo, $admin) {
        $this->idOyente = $idOyente;
        $this->twitter = $twitter;
        $this->idPais = $idPais;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->mecenas = $mecenas;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->telefono = $telefono;
        $this->clave = $clave;
        $this->activo = $activo;
        $this->admin = $admin;
    }

    //GET ----------------------------------------------------------------------
    function getIdOyente() {
        return $this->idOyente;
    }

    function getTwitter() {
        return $this->twitter;
    }

    function getIdPais() {
        return $this->idPais;
    }

    function getLat() {
        return $this->lat;
    }

    function getLon() {
        return $this->lon;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getApellido() {
        return $this->apellido;
    }

    function getEmail() {
        return $this->email;
    }

    function getMecenas() {
        return $this->mecenas;
    }

    function getFechaNacimiento() {
        return $this->fechaNacimiento;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getClave() {
        return $this->clave;
    }

    function getActivo() {
        return $this->activo;
    }

    function getAdmin() {
        return $this->admin;
    }

    //SET ----------------------------------------------------------------------
    function setIdOyente($idOyente) {
        $this->idOyente = $idOyente;
    }

    function setTwitter($twitter) {
        $this->twitter = $twitter;
    }

    function setIdPais($idPais) {
        $this->idPais = $idPais;
    }

    function setLat($lat) {
        $this->lat = $lat;
    }

    function setLon($lon) {
        $this->lon = $lon;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setMecenas($mecenas) {
        $this->mecenas = $mecenas;
    }

    function setFechaNacimiento($fechaNacimiento) {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setClave($clave) {
        $this->clave = $clave;
    }

    function setActivo($activo) {
        $this->activo = $activo;
    }

    public static function existeEmail($email, $bd){
        $bd->setConsulta("select count(email) as existe from oyentes where email = ?");
        $bd->ejecutar($email);
        $it = $bd->resultado();
        return $it["existe"] > 0;
    }

    public static function existeTwitter($twitter, $bd){
        $bd->setConsulta("select count(twitter) as existe from oyentes where email = ?");
        $bd->ejecutar($twitter);
        $it = $bd->resultado();
        return $it["existe"] > 0;
    }

    public function altaBd($bd){
        $bd->setConsulta("insert into oyentes (twitter, id_pais, nombre, apellido, email, fecha_nacimiento, telefono, clave) values (?, ?, ?, ?, ?, ".$this->fechaNacimiento.", ?, ?)");
        $parametros = array($this->twitter, $this->idPais, $this->nombre, $this->apellido, $this->email, $this->telefono, $this->clave);
        $bd->ejecutar($parametros);
        return $bd->filasModificadas() > 0;
    }

    public static function asociarInteres($email, $intereses, $bd){
        $bd->setConsulta("select id_oyente from oyentes where email = ?");
        $bd->ejecutar($email);
        $it = $bd->resultado();
        $idOyente = $it["id_oyente"];
        $bd->setConsulta("insert into inte_oyen(id_oyente, id_interes) values ($idOyente, ?)");
        foreach ($intereses as $interes){
            $bd->ejecutar($interes);
        }
    }

    public static function confirmarUsuario($clave, $email, $bd){
        $oyente = Oyente::oyentePorEmail($bd, $email);
        if(isset($oyente) && $clave == sha1($oyente->getClave().$oyente->getEmail()."pepito")){
            $fecha = date("Y-m-d H:i:s");
            $bd->setConsulta("update oyentes set activo = str_to_date('".$fecha."', '%Y-%m-%d %h:%i:%s') where id_oyente = ?");
            $bd->ejecutar($oyente->getIdOyente());
            $oyente->activo = $fecha;
            if($bd->filasModificadas() > 0){
                return $oyente;
            }
        }
        return null;
    }

    public static function oyentePorEmail($bd, $email){
        $bd->setConsulta("select * from oyentes where email = ?");
        $bd->ejecutar($email);
        if($it = $bd->resultado()){
            $oyente = new Oyente($it["id_oyente"], $it["twitter"], $it["id_pais"], $it["lat"], $it["lon"], $it["nombre"],
                    $it["apellido"], $it["email"], $it["mecenas"], $it["fecha_nacimiento"], $it["telefono"], $it["clave"], $it["activo"], $it["admin"]);
            return $oyente;
        }
        return null;
    }

    public static function login($clave, $email, $bd){
        $oyente = Oyente::oyentePorEmail($bd, $email);
        if($oyente->clave == $clave){
            return $oyente;
        }
        return null;
    }

    public static function cambiarCoordenada($idOyente, $coordenada, $bd){
        $bd->setConsulta("update oyentes set lat = ?, lon = ? where id_oyente = ?");
        $bd->ejecutar(array($coordenada[0], $coordenada[1], $idOyente));
        return $bd->filasModificadas() > 0;
    }
    
    public static function oyentePorId($idOyente, $bd){
        $bd->setConsulta("select * from oyentes where id_oyente = ?");
        $bd->ejecutar($idOyente);
        if($it = $bd->resultado()){
            $oyente = new Oyente($it["id_oyente"], $it["twitter"], $it["id_pais"], $it["lat"], $it["lon"], $it["nombre"],
                    $it["apellido"], $it["email"], $it["mecenas"], $it["fecha_nacimiento"], $it["telefono"], $it["clave"], $it["activo"], $it["admin"]);
            return $oyente;
        }
        return null;
    }

    public static function oyentePorPais($idPais, $bd){
        $bd->setConsulta("select o.* from oyentes o inner join paises p on p.id_pais = o.id_pais where o.id_pais = ?");
        $bd->ejecutar($idPais);
        $lista = array();
        while($it = $bd->resultado()){
            $oyente = new Oyente($it["id_oyente"], $it["twitter"], $it["id_pais"], $it["lat"], $it["lon"], $it["nombre"],
                    $it["apellido"], $it["email"], $it["mecenas"], $it["fecha_nacimiento"], $it["telefono"], $it["clave"], $it["activo"], $it["admin"]);
            array_push($lista, $oyente);
        }
        return $lista;
    }

    public static function oyentesPorInteres($idInteres, $bd){
        $bd->setConsulta("select o.* from oyentes o inner join inte_oyen p on p.id_oyente = o.id_oyente where p.id_interes = ?");
        $bd->ejecutar($idInteres);
        $lista = array();
        while($it = $bd->resultado()){
            $oyente = new Oyente($it["id_oyente"], $it["twitter"], $it["id_pais"], $it["lat"], $it["lon"], $it["nombre"],
                    $it["apellido"], $it["email"], $it["mecenas"], $it["fecha_nacimiento"], $it["telefono"], $it["clave"], $it["activo"], $it["admin"]);
            array_push($lista, $oyente);
        }
        return $lista;
    }
}