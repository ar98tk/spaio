import { Outlet } from 'react-router';
import Header3 from '../Components/Header/Header3';
import Footer3 from '../Components/Footer/Footer3';

const Layout3 = () => {
    return (
        <div className='main-page-area2'>
            <Header3></Header3>
            <Outlet></Outlet>
            <Footer3></Footer3>
        </div>
    );
};

export default Layout3;