/* global FontAwesomeConfig */
import fontawesome from '@fortawesome/fontawesome';
import faCheck from '@fortawesome/fontawesome-free-solid/faCheck';
import faTrashAlt from '@fortawesome/fontawesome-free-solid/faTrashAlt';
import faPlusCircle from '@fortawesome/fontawesome-free-solid/faPlusCircle';
import faDownload from '@fortawesome/fontawesome-free-solid/faDownload';
import faArrowUp from '@fortawesome/fontawesome-free-solid/faArrowCircleUp';
import faArrowDown from '@fortawesome/fontawesome-free-solid/faArrowCircleDown';
fontawesome.config = {
  autoReplaceSvg: 'nest'
};
fontawesome.library.add(
    faCheck, faTrashAlt, faPlusCircle, faDownload, faArrowUp, faArrowDown
);