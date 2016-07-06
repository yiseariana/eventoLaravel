<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Log;

class RegistroApi extends Controller {

    public function postRegistro(Request $request) {
        $this->validate($request, [
            'nombre' => 'required',
            'cedula' => 'required',
            'correo' => 'required',
        ]);

        if (!$request->hasFile('recibo')) {
            return response()->json(['data' => 'Debe adjuntar el recibo de pago'], 400)
                            ->setCallback($request->input('callback'));
        }

        $nombre = $request->get('nombre');
        $cedula = $request->get('cedula');
        $pasaporte = $request->get('pasaporte');
        $nacionalidad = $request->get('nacionalidad');
        $departamento = $request->get('departamento');
        $municipio = $request->get('municipio');
        $telefono = $request->get('telefono');
        $correo = $request->get('correo');
        $entidad = $request->get('entidad');
        $ocupacion = $request->get('ocupacion');
        $ruta = $request->get('ruta');
        $interes = $request->get('interes');

        if ($interes == 'Ponente' && !$request->hasFile('rPonencia')) {
            return response()->json(['data' => 'Debe adjuntar el resumen de la ponencia'], 400)
                            ->setCallback($request->input('callback'));
        }

        $nombreEntidad = $request->get('nEntidad');
        $nombreTrabajo = $request->get('nTrabajo');
        $concurso = $request->get('concurso');

        if ($concurso != 'No aplica' && !$request->hasFile('rConcurso')) {
            return response()->json(['data' => 'Debe adjuntar el resumen del trabajo'], 400)
                            ->setCallback($request->input('callback'));
        }

        $tituloProducto = $request->get('tProducto');


        $registro = \App\Registro::where('cedula', $cedula)->first();

        if ($registro == null) {
            Log::info("Registro inexistente");
            $registro = new \App\Registro;
        }

        $registro->nombre = $nombre;
        $registro->cedula = $cedula;
        $registro->pasaporte = $pasaporte;
        $registro->nacionalidad = $nacionalidad;
        $registro->departamento = $departamento;
        $registro->municipio = $municipio;
        $registro->telefono = $telefono;
        $registro->correo = $correo;
        $registro->entidad = $entidad;
        $registro->ocupacion = $ocupacion;
        $registro->ruta = $ruta;
        $registro->interes = $interes;
        $registro->n_entidad = $nombreEntidad;
        $registro->n_trabajo = $nombreTrabajo;
        $registro->concurso = $concurso;
        $registro->t_producto = $tituloProducto;
        $registro->save();
        Log::info($registro);

        $recibo = $request->file('recibo');
        $rPonencia = $request->file('rPonencia');
        $rConcurso = $request->file('rConcurso');
        $destinationPath = storage_path() . '/uploads';

        if ($rPonencia != null) {
            if (!$rPonencia->move($destinationPath, 'Ponencia_' . $cedula . "_" . $rPonencia->getClientOriginalName())) {
                return response()->json(['data' => 1], 400)
                                ->setCallback($request->input('callback'));
            } else {
                $registro->n_ponencia = 'Ponencia_' . $cedula . "_" . $rPonencia->getClientOriginalName();
                $registro->save();
            }
        }

        if ($rConcurso != null) {
            if (!$rConcurso->move($destinationPath, 'Concurso_' . $cedula . "_" . $rConcurso->getClientOriginalName())) {
                return response()->json(['data' => 2], 400)
                                ->setCallback($request->input('callback'));
            } else {
                $registro->n_concurso = 'Concurso' . $cedula . "_" . $rConcurso->getClientOriginalName();
                $registro->save();
            }
        }

        if (!$recibo->move($destinationPath, 'Recibo_' . $cedula . "_" . $recibo->getClientOriginalName())) {
            return response()->json(['data' => 3], 400)
                            ->setCallback($request->input('callback'));
        } else {
            $registro->n_recibo = 'Recibo_' . $cedula . "_" . $recibo->getClientOriginalName();
            $registro->save();
        }


        return response()->json(['data' => 'Registro completado'], 200)
                        ->setCallback($request->input('callback'));
    }

    public function getRegistros(Request $request) {
        $registros = \App\Registro::all();

        return response()->json(['data' => $registros], 200)
                        ->setCallback($request->input('callback'));
    }

}
