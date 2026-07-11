import { useEffect, useRef, useState } from "react";

const Faq2 = () => {

            const faqContent = [
            {title:'What pricing plans do you offer for your app?', content:'No, we are transparent about our pricing structure, and there are hidden fees or additional charges. The price you see is the price you pay'},
            {title:'Are there any hidden fees or additional charges?', content:'No, we are transparent about our pricing structure, and there are hidden fees or additional charges. The price you see is the price you pay'},
            {title:'What payment methods do you accept?', content:'No, we are transparent about our pricing structure, and there are hidden fees or additional charges. The price you see is the price you pay'},
          ]; 
    
          const accordionContentRef = useRef(null);
          const [openItemIndex, setOpenItemIndex] = useState(-1);
          const [firstItemOpen, setFirstItemOpen] = useState(true);
        
      const handleItemClick = (index: number) => {
        if (index === openItemIndex) {
          setOpenItemIndex(-1);
        } else {
          setOpenItemIndex(index);
        }
      };
      
      useEffect(() => {
          if (firstItemOpen) {
              const timer = setTimeout(() => {
                  setOpenItemIndex(0);
                  setFirstItemOpen(false);
              }, 0);
              return () => clearTimeout(timer);
          }
      }, [firstItemOpen]); 


    return (
<section className="cs_faq cs_style_1 cs_gray_bg position-relative overflow-hidden">
      <div className="cs_height_120 cs_height_lg_80"></div>
      <div className="container">
        <div className="row cs_gap_y_30 align-items-center position-relative z-1">
          <div className="col-lg-6 order-lg-2">
            <div className="cs_faq_thumbnail">
              <img src="/assets/img/faq-img-1.png" alt="FAQ image" />
            </div>
          </div>
          <div className="col-lg-6 order-lg-1">
            <div className="cs_faq_content">
              <div className="cs_section_heading cs_style_1 cs_mb_20">
                <div className="cs_section_subtitle cs_fs_18 cs_heading_color cs_mb_22">
                  <img src="/assets/img/icons/star-1.svg" alt="Star icon" />
                  <span>faq</span>
                  <img src="/assets/img/icons/star-1.svg" alt="Star icon" />
                </div>
                <h2 className="cs_section_title cs_fs_48 cs_semibold mb-0 wow fadeInDown">Frequently Ask Questions</h2>
              </div>
              <p className="cs_card_desc cs_mb_56">Highlight the most important features and functionalities of the app. Use concise descriptions or bullet points to emphasize what sets your app</p>
              <div className="cs_faq_wrapper_1">

                {faqContent.map((item, index) => (
                <div key={index} className={`cs_accordian cs_style_1 cs_gray_bg cs_radius_10 position-relative ${index === openItemIndex ? "active" : "" }`} >
                  <div onClick={() => handleItemClick(index)} className="cs_accordian_head">
                    <h3 className="cs_accordian_title cs_fs_20 cs_semibold mb-0">{item.title}</h3>
                    <span className="cs_accordian_toggler position-absolute"></span>
                  </div>
                  <div ref={accordionContentRef} className="cs_accordian_body">
                     <p>{item.content}</p>
                  </div>
                </div>
                ))}

              </div>
            </div>
          </div>
        </div>
        <div className="cs_faq_shape_1 position-absolute">
          <img src="/assets/img/vector-5.svg" alt="Vector shape" />
        </div>
        <div className="cs_faq_shape_2 position-absolute">
          <img src="/assets/img/star-shape.svg" alt="Star shape" />
        </div>
      </div>
      <div className="cs_height_120 cs_height_lg_80"></div>
    </section>
    );
};

export default Faq2;