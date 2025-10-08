import Auth from './Auth'
import Crm from './Crm'
import Settings from './Settings'
const Controllers = {
    Auth: Object.assign(Auth, Auth),
Crm: Object.assign(Crm, Crm),
Settings: Object.assign(Settings, Settings),
}

export default Controllers