<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use DB;
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
            if (!$rPonencia->move($destinationPath, 'Ponencia_' . $cedula . "." . $rPonencia->getClientOriginalExtension())) {
                return response()->json(['data' => 1], 400)
                                ->setCallback($request->input('callback'));
            } else {
                $registro->n_ponencia = 'Ponencia_' . $cedula . "." . $rPonencia->getClientOriginalExtension();
                $registro->mime_ponencia = $rPonencia->getClientMimeType();
                $registro->save();
            }
        }

        if ($rConcurso != null) {
            if (!$rConcurso->move($destinationPath, 'Concurso_' . $cedula . "." . $rConcurso->getClientOriginalExtension())) {
                return response()->json(['data' => 2], 400)
                                ->setCallback($request->input('callback'));
            } else {
                $registro->n_concurso = 'Concurso_' . $cedula . "." . $rConcurso->getClientOriginalExtension();
                $registro->mime_concurso = $recibo->getClientMimeType();
                $registro->save();
            }
        }

        if (!$recibo->move($destinationPath, 'Recibo_' . $cedula . "." . $recibo->getClientOriginalExtension())) {
            return response()->json(['data' => 3], 400)
                            ->setCallback($request->input('callback'));
        } else {
            $registro->n_recibo = 'Recibo_' . $cedula . "." . $recibo->getClientOriginalExtension();
            $registro->mime_recibo = $recibo->getClientMimeType();
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

    public function getArchivo(Request $request) {
        $archivo = $request->get('archivo');
        $tipo = $request->get('tipo');
        $path = storage_path() . '/uploads';
        $ruta = $path . '/' . $archivo;
        $registro = DB::table('registro')
                        ->where($tipo, $archivo)->first();
        //$mime = \Storage::mimeType($file->mime());

        if ($registro == null) {
            return new Response("hola", 200);
        }

        $headers = array(
            'Content-Type: ' . $registro->mime_recibo,
        );

        /* return (new Response($file, 200))
          ->header('Content-Type', $registro->mime_recibo); */

        return response()->download($ruta, $archivo, $headers);
    }

    public function getAprobar(Request $request) {
        $cedula = $request->get('cedula');

        $registro = \App\Registro::where('cedula', $cedula)->first();

        Log::info($registro->nombre);

        if ($registro == null) {
            return response()->json(['data' => '0'], 401)
                            ->setCallback($request->input('callback'));
        }

        $registro->aprobado = true;
        $registro->save();

        return response()->json(['data' => '1'], 200)
                        ->setCallback($request->input('callback'));
    }

    public function getDesaprobar(Request $request) {
        $cedula = $request->get('cedula');

        $registro = \App\Registro::where('cedula', $cedula)->first();

        Log::info($registro->nombre);

        if ($registro == null) {
            return response()->json(['data' => '0'], 401)
                            ->setCallback($request->input('callback'));
        }

        $registro->aprobado = false;
        $registro->save();

        return response()->json(['data' => '0'], 200)
                        ->setCallback($request->input('callback'));
    }

}
