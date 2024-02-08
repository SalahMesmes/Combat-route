
import { Alert } from "bootstrap"

function sendFetch( 
    url, 
    paramList={}, 
    responseType='json',
    method='post' )
{
 //   if( typeof paramList !== 'object' ) return response.error();
    return fetch( url, {
        method: method,
        body: JSON.stringify(paramList)
    })
    .then( response => {
        return responseType === 'json' ? response.json() : response.text()
    })
}

$('.persoHit').on( 'click', (e)=> {
    const elem = e.target
    const idHit = elem.dataset.id
    const parentElem = elem.parentElement
    const url = parentElem.dataset.url

    const paramList = {
        'idhit': idHit
    }
    sendFetch( url, paramList)
        .then( data => {
            if( !data.error ) {
                const persoElem = '#degats-perso-' + data.persoToHitId
                $('.mess-action-canva').removeClass('hide').show(100).delay(1000).hide(100)
                $('.mess-action').html( data.message )
                $(persoElem).text( data.persoDegats )
            }
        })
})
