import { Link } from 'react-router';

const ServiceCard1 = ({addclass,serviceicon,title,featureList,btnname,btnurl} : { addclass?: string; serviceicon: string; title: string; featureList: string[]; btnname: string; btnurl: string }) => {
    return (
          <div className="cs_feature_item cs_white_bg cs_radius_20">
            <span className={`cs_feature_icon cs_center cs_radius_12 cs_mb_15 ${addclass}`} >
            <img src={serviceicon} alt="Coding icon" />
            </span>
            <h3 className="cs_fs_24 cs_semibold cs_mb_6">
              <Link to={btnurl} aria-label="Service details link">{title}</Link>
            </h3>
            <ul className="cs_features_list cs_mp_0">
            {featureList?.map((item, index) => (
              <li key={index}>
                <img src="/assets/img/icons/caret-icon.svg" alt="Caret icon" />
                <span>{item}</span>
              </li>
            ))}
            </ul>
            <Link to={btnurl} aria-label="Service details link" className="cs_text_btn cs_fs_14 cs_bold text-uppercase">
            <span>{btnname}</span>
            <span className="cs_btn_icon"><i className="bi bi-arrow-right"></i></span>
            </Link>
          </div>
    );
};

export default ServiceCard1;