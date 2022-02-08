<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {

        if (!$request->has('simple')) {

            $element = array(
                'userId'        => $this->user_id,
                'nroCta'        => $this->nrocta,
                'nroDoc'        => $this->data['nrodoc'],
                'razonSocial'   => $this->data['respon'] ?? null,
                'responsable'   => $this->data['usrvtmcl'] ?? null,
                'phone'         => $this->data['telefn'] ?? null,
                'address'       => $this->data['address'] ?? null,
                'transport'     => $this->data['transportista'] ?? null,
                'seller'        => $this->data['vendedor'] ?? null
            );

        } else {

            $element = array(
                'userId'        => $this->user_id,
                'nroCta'        => $this->nrocta,
                'razonSocial'   => $this->data['respon'] ?? null,
                'responsable'   => $this->data['usrvtmcl'] ?? null,
                'phone'         => $this->data['telefn'] ?? null,
                'address'       => $this->data['address'] ?? null
            );

        }
        return $element;

    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function withResponse($request, $response)
    {
        $response->header('X-Value', 'True');
    }
}
