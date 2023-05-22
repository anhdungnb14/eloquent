<?php

class OneToMany
{
    /**
     * @param $dataMain
     * @param $oneToMany
     * @param $pdo
     */
    public function oneToMany($dataMain, $oneToMany, $pdo, $primaryKey)
    {
        list($tableReltion, $foreignKey) = array_values($oneToMany);
        $idGroup = [];
        foreach ($dataMain as $data) {
            $idGroup[] = $data->$primaryKey;
        }
        $idGroup = implode(', ', $idGroup);
        $sqlRelation = "SELECT * FROM {$tableReltion} WHERE {$foreignKey} IN ({$idGroup})";
        $stmt = $pdo->prepare($sqlRelation);
        $stmt->execute();
        $dataRelation = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $dataRelation ?? NULL;
    }
}
