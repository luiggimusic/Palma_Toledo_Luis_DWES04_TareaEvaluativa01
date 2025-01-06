<?php

/** He creado el modelo User.
 * Aquí defino el modelo con su tipo de dato e importo el fichero JSON
 **/
class User
{
    private int $id;
    private string $name;
    private string $surname;
    private string $dni;
    private string $dateOfBirth;
    private string $department;

    // Constructor para inicializar propiedades

    public function __construct(int $id, string $name, string $surname, string $dni, string $dateOfBirth, string $department)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->dni = $dni;
        $this->dateOfBirth = $dateOfBirth;
        $this->department = $department;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getSurname()
    {
        return $this->surname;
    }
    public function getDni()
    {
        return $this->dni;
    }
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }
    public function getDepartment()
    {
        return $this->department;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }
    public function setDni($dni)
    {
        $this->dni = $dni;
    }
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
    }
    public function setDepartment($department)
    {
        $this->department = $department;
    }


    private static function getFilePath()
    { // Por visualización he creado esta función decoficiando el JSON y poder usarlo en las otras funciones
        return __DIR__ . '/../models/data/users.json'; // Ruta del archivo JSON
    }

    private static function datosJsonParseados()
    { // Por visualización he creado esta función decodificando el JSON y poder usarlo en las otras funciones
        return json_decode(self::getAll(), true);
    }

    // Método estático getAll para obtener todos los usuarios
    public static function getAll()
    {
        $filePath = self::getFilePath(); // Ruta del archivo JSON
        $jsonData = file_get_contents($filePath); // Leo el archivo JSON
        return $jsonData; // Retorna el array con los datos de los usuarios
    }

    public static function getById($id)
    {
        $usersArray = self::datosJsonParseados();
        return getElementById($usersArray, $id);
    }

    public static function create($userData)
    {
        $usersArray = self::datosJsonParseados();

        $arrayErrores = validacionesDeUsuario($userData);

        if (existeDNI($usersArray, $userData['dni'])) { // Verifico si el DNI ya existe
            $arrayErrores["dni"] = 'El DNI ya está registrado';
        }

        if (count($arrayErrores) > 0) { // Si el array de errores es mayor que 0, entonces  creo un array asociativo que mostrará la respuesta
            print_r($arrayErrores);
        } else {
            $newId = nextId($usersArray); // Llamo a la función nextId para asignarle un id correcto al nuevo usuario

            // Creo un objeto User y asigno los datos con setters
            $newUser = new self($newId, '', '', '', '', ''); // Inicializo el objeto con el nuevo ID
            $newUser->setName($userData['name']);
            $newUser->setSurname($userData['surname']);
            $newUser->setDni($userData['dni']);
            $newUser->setDateOfBirth($userData['dateOfBirth']);
            $newUser->setDepartment($userData['department']);

            // Convierto el objeto User a un array para guardarlo en el JSON
            $usersArray[] = [
                'id' => $newUser->getId(),
                'name' => $newUser->getName(),
                'surname' => $newUser->getSurname(),
                'dni' => $newUser->getDni(),
                'dateOfBirth' => $newUser->getDateOfBirth(),
                'department' => $newUser->getDepartment(),
            ];
            // Guardo en el JSON
            $newJsonData = json_encode($usersArray, JSON_PRETTY_PRINT);
            return file_put_contents(self::getFilePath(), $newJsonData) !== false;
        }
    }
    public static function update($id, $newData)
    {
        $usersArray = self::datosJsonParseados();

        // Busco el usuario por ID
        $userConfirmed = false;
        foreach ($usersArray as &$data) { // Uso la referencia, para que los cambios que realizo 
            //en el array dentro del bucle se apliquen al array original.

            if ($data['id'] === $id) {
                $arrayErrores = validacionesDeUsuario($newData);

                if (existeIdExcluyendo($usersArray, $newData['dni'], $id,'dni')) { // Evito que se duplique un DNI
                    $arrayErrores["dni"] = 'El DNI ya está registrado';
                }
                if (count($arrayErrores) > 0) { // Si el array de errores es mayor que 0, entonces  creo un array asociativo que mostrará la respuesta
                    print_r($arrayErrores);
                    break;
                }

                // Creo un objeto User con los datos actuales
                $user = new self(
                    $data['id'],
                    $data['name'],
                    $data['surname'],
                    $data['dni'],
                    $data['dateOfBirth'],
                    $data['department']
                );

                // Uso los setters para actualizar los datos
                if (isset($newData['name'])) {
                    $user->setName($newData['name']);
                }
                if (isset($newData['surname'])) {
                    $user->setSurname($newData['surname']);
                }
                if (isset($newData['dni'])) {
                    $user->setDni($newData['dni']);
                }
                if (isset($newData['dateOfBirth'])) {
                    $user->setDateOfBirth($newData['dateOfBirth']);
                }
                if (isset($newData['department'])) {
                    $user->setDepartment($newData['department']);
                }

                // Actualizo los datos en el array
                $data = [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'surname' => $user->getSurname(),
                    'dni' => $user->getDni(),
                    'dateOfBirth' => $user->getDateOfBirth(),
                    'department' => $user->getDepartment(),
                ];
                $userConfirmed  = true;
                unset($data);
            }
        }
        if (!$userConfirmed) {
            return false;
        }

        $newJsonData = json_encode($usersArray, JSON_PRETTY_PRINT);
        return file_put_contents(self::getFilePath(), $newJsonData) !== false;
    }

    public static function delete($id)
    {
        $usersArray = self::datosJsonParseados();

        // Busco el usuario por ID
        $result = getElementById($usersArray, $id);
        if (!$result) {
            echo "No se ha encontrado el usuario con id: " . $id . "\n";
            return false;
        } else {
            unset($usersArray[$id]);
            $json = json_encode(array_values($usersArray), JSON_PRETTY_PRINT);
            file_put_contents(self::getFilePath(), $json);
            return true;
        };
    }
}

/*********** Funciones necesarias ***********/

function validacionesDeUsuario($data)
{
    // Valido los datos insertados en body (formulario) y voy completando el array $arrayErrores con los errores que aparezcan
    $arrayErrores = array();
    if (empty($data["name"])) {
        $arrayErrores["name"] = 'El nombre es obligatorio';
    }
    if (empty($data["surname"])) {
        $arrayErrores["surname"] = 'El apellido es obligatorio';
    }
    if (empty($data["dni"])) {
        $arrayErrores["dni"] = 'El DNI es obligatorio';
    } elseif (!validarDNI($data["dni"])) {
        $arrayErrores["dni"] = 'El DNI no es válido';
    }
    if (empty($data["dateOfBirth"])) {
        $arrayErrores["dateOfBirth"] = 'La fecha de nacimiento es obligatoria';
    }
    if (empty($data["department"])) {
        $arrayErrores["department"] = 'El departamento es obligatorio';
    }
    return $arrayErrores;
}
