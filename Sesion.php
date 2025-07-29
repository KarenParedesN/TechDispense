<?php

class Sesion {
    public string $cue;
    public array $rolIds;
    public ?string $empId;

    public function __construct(string $cue, array $rolIds) {
        $this->cue = $cue;
        $this->rolIds = $rolIds;
        $this->empId = $_SESSION['EMP_ID'] ?? null;
    }
}

