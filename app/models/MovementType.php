<?php

/** Definición del modelo con su tipo de dato e importo el fichero JSON
 **/
class MovementType
{
    private int $id;
    private string $movementId;
    private string $movementName;

    // Constructor para inicializar propiedades

    public function __construct(int $id, string $movementId, string $movementName)
    {
        $this->id = $id;
        $this->movementId = $movementId;
        $this->movementName = $movementName;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getMovementId()
    {
        return $this->movementId;
    }
    public function getMovementName()
    {
        return $this->movementName;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setMovementId($movementId)
    {
        $this->movementId = $movementId;
    }
    public function setMovementName($movementName)
    {
        $this->movementName = $movementName;
    }
    private static function getFilePath()
    { // Por visualización he creado esta función decoficando el JSON y poder usarlo en las otras funciones
        return __DIR__ . '/../models/data/movementType.json'; // Ruta del archivo JSON
    }

    private static function datosJsonParseados()
    { // Por visualización he creado esta función decodificando el JSON y poder usarlo en las otras funciones
        return json_decode(self::getAll(), true);
    }

    // Método estático getAll para obtener todos los datos del JSON
    public static function getAll()
    {
        $filePath = self::getFilePath(); // Ruta del archivo JSON
        $jsonData = file_get_contents($filePath); // Leo el archivo JSON
        return $jsonData; // Retorno el array con los datos
    }

    public static function getById($id)
    {
        $dataArray = self::datosJsonParseados();
        return getElementById($dataArray, $id);
    }

    public static function create($newData)
    {
        $dataArray = self::datosJsonParseados();

        $arrayErrores = validacionesDeMovementType($newData);

        if (existsObjectid($dataArray, $newData['movementId'], 'movementId')) {
            $arrayErrores["movementId"] = 'El ID de este tipo de movimiento ya está registrado';
        }
        if (count($arrayErrores) > 0) { // Si el array de errores es mayor que 0, entonces  creo un array asociativo que mostrará la respuesta
            print_r($arrayErrores);
        } else {
            $newId = nextId($dataArray); // Llamo a la función nextId para asignarle un id correcto al nuevo objeto

            // Creo un objeto de la clase y asigno los datos con setters
            $newElement = new self($newId, '', '', '', '', ''); // Inicializo el objeto con el nuevo ID
            $newElement->setMovementId($newData['movementId']);
            $newElement->setMovementName($newData['movementName']);

            // Convierto el objeto de la clase a un array para guardarlo en el JSON
            $dataArray[] = [
                'id' => $newElement->getId(),
                'movementId' => $newElement->getMovementId(),
                'movementName' => $newElement->getMovementName(),
            ];
            // Guardo en el JSON
            $newJsonData = json_encode($dataArray, JSON_PRETTY_PRINT);
            return file_put_contents(self::getFilePath(), $newJsonData) !== false;
        }
    }

    public static function update($id, $newData)
    {
        $dataArray = self::datosJsonParseados();

        // Busco por ID
        $elementConfirmed = false;
        foreach ($dataArray as &$data) { // Uso la referencia "&", para que los cambios que realizo 
            //en el array dentro del bucle se apliquen al array original.

            if ($data['id'] === $id) {
                $arrayErrores = validacionesDeMovementType($newData);

                if (existeIdExcluyendo($dataArray, $newData['movementId'], $id, 'movementId')) { // Evito que se duplique el Id de la clase
                    $arrayErrores['movementId'] = 'El ID ya está registrado';
                }
                if (count($arrayErrores) > 0) { // Si el array de errores es mayor que 0, entonces  creo un array asociativo que mostrará la respuesta
                    print_r($arrayErrores);
                    break;
                }

                // Creo un objeto con los datos actuales
                $element = new self(
                    $data['id'],
                    $data['movementId'],
                    $data['movementName'],
                );

                // Uso los setters para actualizar los datos
                if (isset($newData['movementId'])) {
                    $element->setMovementId($newData['movementId']);
                }
                if (isset($newData['movementName'])) {
                    $element->setMovementName($newData['movementName']);
                }

                // Actualizo los datos en el array
                $data = [
                    'id' => $element->getId(),
                    'movementId' => $element->getMovementId(),
                    'movementName' => $element->getMovementName(),
                ];
                $elementConfirmed  = true;
                unset($data);
            }
        }
        if (!$elementConfirmed) {
            return false;
        }

        $newJsonData = json_encode($dataArray, JSON_PRETTY_PRINT);
        return file_put_contents(self::getFilePath(), $newJsonData) !== false;
    }

    public static function delete($id)
    {
        $dataArray = self::datosJsonParseados();

        // Busco por ID
        $result = getElementById($dataArray, $id);
        if (!$result) {
            echo "No se ha encontrado el tipo de movimiento con id: " . $id . "\n";
            return false;
        } else {
            unset($dataArray[$id]);
            $json = json_encode(array_values($dataArray), JSON_PRETTY_PRINT);
            file_put_contents(self::getFilePath(), $json);
            return true;
        };
    }
}

/*********** Funciones necesarias ***********/

function validacionesDeMovementType($data)
{
    // Valido los datos insertados en body (formulario) y voy completando el array $arrayErrores con los errores que aparezcan
    $arrayErrores = array();
    if (empty($data["movementId"])) {
        $arrayErrores["movementId"] = 'El ID del tipo de movimiento es obligatorio';
    }
    if (empty($data["movementName"])) {
        $arrayErrores["movementName"] = 'El nombre del tipo de movimiento es obligatorio';
    }
    return $arrayErrores;
}
