<?php

class NormalizeData
{
    /**
     * @param $dataMain
     * @param $dataRelation
     * @param $oneToMany
     */
    public function NormalizeData($dataMain, $dataRelation, $oneToMany)
    {
        $dataRelationGroup = [];
        foreach ($oneToMany as $oneToManyItem) {
            list($tableReltion, $foreignKey) = array_values($oneToManyItem);
            foreach ($dataRelation[$tableReltion] as $item) {
                $dataRelationGroup[$tableReltion][$item->$foreignKey][] = $item;
            }
        }
        foreach ($oneToMany as $oneToManyItem) {
            $tableReltion = $oneToManyItem['tableReltion'];
            foreach ($dataMain as $item) {
                $item->$tableReltion = $dataRelationGroup[$tableReltion][$item->id] ?? [];
            }
        }
        return $dataMain ?? NULL;
    }
}
